<?php

namespace App\Filament\Admin\Pages;

use App\Models\SiteContent;
use App\Services\ApifyTrafficService;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class TrafficDomainFilter extends Page implements HasForms
{
    use InteractsWithForms;
    use WithFileUploads;

    protected static ?string $navigationIcon = 'heroicon-o-funnel';
    protected static ?string $navigationLabel = 'Lọc traffic domain';
    protected static ?string $title = 'Lọc traffic theo domain';
    protected static ?string $navigationGroup = 'Thống Kê';
    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.admin.pages.traffic-domain-filter';

    public ?array $data = [];
    /** @var array<int, string> */
    public array $logs = [];

    public static function canAccess(): bool
    {
        return (bool) Filament::auth()->check();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public function mount(): void
    {
        $this->form->fill([
            'apify_token' => $this->getStoredToken() ? '********' : '',
            'actor_id' => 'aqPbs3KeH9aD8b22w',
            'domains_file' => null,
            'traffic' => 100000,
        ]);

        $this->logs = [];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Cấu hình API')
                    ->schema([
                        TextInput::make('apify_token')
                            ->label('Apify token')
                            ->password()
                            ->revealable()
                            ->helperText('Nhập token mới để lưu. Nếu để "********" thì giữ token hiện tại.')
                            ->maxLength(200),
                        TextInput::make('actor_id')
                            ->label('Actor ID')
                            ->helperText('Mặc định: aqPbs3KeH9aD8b22w')
                            ->required()
                            ->maxLength(100),
                    ])->columns(2),
                Section::make('Danh sách domain')
                    ->schema([
                        FileUpload::make('domains_file')
                            ->label('Upload file .txt (mỗi dòng 1 domain)')
                            ->acceptedFileTypes(['text/plain'])
                            ->required()
                            ->storeFiles(false),
                        TextInput::make('traffic')
                            ->label('Ngưỡng Traffic')
                            ->numeric()
                            ->minValue(0)
                            ->default(100000)
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('saveToken')
                ->label('Lưu token')
                ->icon('heroicon-o-key')
                ->color('gray')
                ->action(fn () => $this->saveToken()),
            Action::make('run')
                ->label('Chạy & tải CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(fn () => $this->runAndDownload()),
        ];
    }

    protected function saveToken(): void
    {
        $state = $this->form->getState();
        $token = trim((string) ($state['apify_token'] ?? ''));

        if ($token === '' || $token === '********') {
            Notification::make()
                ->title('Không có thay đổi')
                ->body('Token không đổi.')
                ->warning()
                ->send();
            return;
        }

        $this->storeToken($token);

        $this->form->fill([
            ...$state,
            'apify_token' => '********',
        ]);

        Notification::make()
            ->title('Đã lưu token')
            ->success()
            ->send();
    }

    protected function runAndDownload()
    {
        $state = $this->form->getState();
        $this->logs = [];

        $tokenInput = trim((string) ($state['apify_token'] ?? ''));
        $token = ($tokenInput !== '' && $tokenInput !== '********') ? $tokenInput : ($this->getStoredToken() ?? '');
        if ($token === '') {
            Notification::make()
                ->title('Thiếu token')
                ->body('Vui lòng nhập Apify token và bấm "Lưu token" hoặc nhập token ngay trước khi chạy.')
                ->danger()
                ->send();
            $this->appendLog('Thiếu token Apify, dừng tiến trình.');
            return null;
        }

        if ($tokenInput !== '' && $tokenInput !== '********') {
            $this->storeToken($tokenInput);
            $this->form->fill([
                ...$state,
                'apify_token' => '********',
            ]);
        }

        $actorId = trim((string) ($state['actor_id'] ?? 'aqPbs3KeH9aD8b22w'));
        $file = $state['domains_file'] ?? null;
        if (! $file) {
            Notification::make()
                ->title('Thiếu file domain')
                ->danger()
                ->send();
            $this->appendLog('Thiếu file domain, dừng tiến trình.');
            return null;
        }

        $content = (string) $file->get();
        $domains = collect(preg_split('/\r\n|\r|\n/', $content))
            ->map(fn ($d) => trim((string) $d))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (count($domains) === 0) {
            Notification::make()
                ->title('File rỗng')
                ->body('Không tìm thấy domain hợp lệ trong file.')
                ->danger()
                ->send();
            $this->appendLog('File rỗng hoặc không có domain hợp lệ.');
            return null;
        }

        $this->appendLog('Loaded ' . count($domains) . ' domains từ file.');

        $svc = app(ApifyTrafficService::class);
        $trafficThreshold = (int) ($state['traffic'] ?? 0);

        $rows = [];
        $totalExpected = count($domains);
        $processed = 0;
        $hadError = false;
        $errorMessage = null;

        try {
            $this->appendLog("Gọi Apify actor {$actorId}...");
            $runData = $svc->runTrafficActor($token, $domains, $actorId);
            $datasetId = (string) ($runData['defaultDatasetId'] ?? '');
            if ($datasetId === '') {
                throw new \RuntimeException('Actor không trả về defaultDatasetId.');
            }

            $this->appendLog("Actor đã chạy xong. Dataset ID: {$datasetId}.");

            // Fetch items by pages so we can export partial if token/Apify fails mid-way
            $offset = 0;
            $limit = 1000;
            while (true) {
                $this->appendLog("Đang lấy items từ dataset (offset={$offset}, limit={$limit})...");
                $items = $svc->fetchDatasetItemsPage($token, $datasetId, $offset, $limit);
                if ($items === []) {
                    $this->appendLog('Không còn item nào trong dataset.');
                    break;
                }

                foreach ($items as $item) {
                    if (! is_array($item)) continue;
                    $domain = (string) ($item['SiteName'] ?? '');
                    $eng = is_array($item['Engagments'] ?? null) ? $item['Engagments'] : [];
                    $visitsFormatted = $eng['VisitsFormatted'] ?? null;
                    $pagesPerVisit = $eng['PagePerVisit'] ?? null;
                    $bounceRateRaw = $eng['BounceRate'] ?? null;
                    $timeOnSite = $eng['TimeOnSite'] ?? null;

                    // Format visit duration như H:i:s nếu có seconds
                    $visitDuration = null;
                    if ($timeOnSite !== null && is_numeric($timeOnSite)) {
                        $visitDuration = gmdate('H:i:s', (int) $timeOnSite);
                    }

                    // Global / Country rank nằm ngoài Engagments theo mẫu dữ liệu
                    $globalRank = null;
                    if (is_array($item['GlobalRank'] ?? null)) {
                        $globalRank = $item['GlobalRank']['Rank'] ?? null;
                    }
                    $countryRank = null;
                    if (is_array($item['CountryRank'] ?? null)) {
                        $countryRank = $item['CountryRank']['Rank'] ?? null;
                    }

                    $visitsNum = null;
                    if (isset($eng['Visits']) && is_numeric($eng['Visits'])) {
                        $visitsNum = (int) $eng['Visits'];
                    } elseif (is_string($visitsFormatted) && trim($visitsFormatted) !== '') {
                        $visitsNum = $this->parseVisitsFormatted($visitsFormatted);
                    }

                    $status = ($visitsNum !== null && $visitsNum >= $trafficThreshold) ? 'GET' : '';

                    // Top countries: gộp 3 nước vào một ô: US (20.00%), UK (40.12%), ...
                    $countries = is_array($item['TopCountryShares'] ?? null) ? $item['TopCountryShares'] : [];
                    $topCountries = collect($countries)
                        ->take(3)
                        ->map(function ($c) {
                            if (! is_array($c)) return null;
                            $cc = $c['CountryCode'] ?? null;
                            $val = $c['Value'] ?? null;
                            if ($cc === null || $val === null) return null;
                            $pct = number_format((float) $val, 2) . '%';
                            return $cc . ' (' . $pct . ')';
                        })
                        ->filter()
                        ->implode(', ');

                    // Top keywords: Name/NameKey(Volume), ...
                    $keywords = is_array($item['TopKeywords'] ?? null) ? $item['TopKeywords'] : [];
                    $topKeywords = collect($keywords)
                        ->take(5)
                        ->map(function ($k) {
                            if (! is_array($k)) return null;
                            $name = $k['Name'] ?? ($k['NameKey'] ?? null);
                            $vol = $k['Volume'] ?? null;
                            if ($name === null) return null;
                            return $vol !== null
                                ? "{$name}({$vol})"
                                : $name;
                        })
                        ->filter()
                        ->implode(', ');

                    $rows[] = [
                        $domain,
                        $visitsFormatted,
                        $status,
                        $visitsNum,
                        $visitDuration,
                        $pagesPerVisit,
                        $bounceRateRaw !== null ? number_format((float) $bounceRateRaw, 2) . '%' : null,
                        $globalRank,
                        $countryRank,
                        $topCountries,
                        $topKeywords,
                    ];

                    $processed++;
                    $this->appendLog("Đã xử lý {$processed}/{$totalExpected} record (domain: {$domain}).");
                }

                if (count($items) < $limit) {
                    $this->appendLog('Đã lấy hết items trong dataset.');
                    break;
                }
                $offset += $limit;
            }
        } catch (\Throwable $e) {
            $hadError = true;
            $errorMessage = $e->getMessage();
            $this->appendLog('Lỗi khi gọi Apify: ' . $errorMessage);
        }

        if ($hadError) {
            Notification::make()
                ->title('Lỗi Apify token / API')
                ->body(($errorMessage ? Str::limit($errorMessage, 180) : 'Không thể lấy đủ dữ liệu từ Apify.') . ' Hệ thống sẽ xuất CSV các record đã lấy được (nếu có).')
                ->danger()
                ->send();
        }

        $filename = 'traffic_' . Str::slug($actorId) . '_' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($rows): void {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
            fputcsv($out, [
                'Domain',
                'Monthly Visits',
                'Status',
                'Monthly Visits (number)',
                'Visit Duration',
                'Pages Per Visit',
                'Bounce Rate',
                'Global Rank',
                'Country Rank',
                'Top Countries',
                'Top Keywords',
            ]);
            foreach ($rows as $r) {
                fputcsv($out, $r);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    protected function parseVisitsFormatted(string $value): ?int
    {
        $v = strtoupper(trim($value));
        if ($v === '') return null;

        // Remove commas and spaces
        $v = str_replace([',', ' '], ['', ''], $v);

        // Examples: 123.4K, 2.1M, 100000
        $multiplier = 1;
        if (str_ends_with($v, 'K')) {
            $multiplier = 1000;
            $v = substr($v, 0, -1);
        } elseif (str_ends_with($v, 'M')) {
            $multiplier = 1000000;
            $v = substr($v, 0, -1);
        } elseif (str_ends_with($v, 'B')) {
            $multiplier = 1000000000;
            $v = substr($v, 0, -1);
        }

        $num = is_numeric($v) ? (float) $v : null;
        if ($num === null) return null;

        return (int) round($num * $multiplier);
    }

    protected function appendLog(string $message): void
    {
        $this->logs[] = '[' . now()->format('H:i:s') . '] ' . $message;
        // Giữ tối đa 200 dòng log
        if (count($this->logs) > 200) {
            $this->logs = array_slice($this->logs, -200);
        }
    }

    protected function tokenKey(): string
    {
        $userId = Filament::auth()->id() ?? '0';
        return "apify_token.user.{$userId}";
    }

    protected function getStoredToken(): ?string
    {
        $raw = SiteContent::get($this->tokenKey());
        if (! is_string($raw) || trim($raw) === '') {
            return null;
        }
        try {
            return Crypt::decryptString($raw);
        } catch (\Throwable) {
            return null;
        }
    }

    protected function storeToken(string $token): void
    {
        SiteContent::set($this->tokenKey(), Crypt::encryptString($token));
    }
}

