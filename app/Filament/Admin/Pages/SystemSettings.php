<?php

namespace App\Filament\Admin\Pages;

use App\Support\AdminSettings;
use App\Support\AutoPostSettings;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SystemSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.admin.pages.system-settings';

    protected static ?string $navigationLabel = 'Cài đặt hệ thống';

    protected static ?string $title = 'Cài đặt hệ thống';

    protected static ?string $navigationGroup = 'Cài đặt';

    protected static ?int $navigationSort = 9999;

    public ?array $data = [];

    public static function canAccess(): bool
    {
        $user = Filament::auth()->user();

        return (bool) ($user && method_exists($user, 'isAdmin') && $user->isAdmin());
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public function mount(): void
    {
        $this->form->fill([
            'apify_token' => AdminSettings::getEncrypted('apify_token') ? '********' : '',
            'apify_actor_id' => (string) AdminSettings::get('apify_actor_id', 'aqPbs3KeH9aD8b22w'),
            'traffic_threshold_default' => (int) AdminSettings::get('traffic_threshold_default', 100000),
            'gemini_api_key' => AdminSettings::getEncrypted('gemini_api_key') ? '********' : '',
            'gemini_model' => (string) AdminSettings::get('gemini_model', config('gemini.model', 'gemini-1.5-flash-latest')),
            'gemini_timeout' => (int) AdminSettings::get('gemini_timeout', config('gemini.timeout', 60)),
            'site_contact_email' => (string) AdminSettings::get('site_contact_email', config('mail.from.address', 'contact@example.com')),
            'auto_blog_enabled' => (bool) AdminSettings::get('auto_blog_enabled', true),
            'auto_blog_daily_count' => (int) AdminSettings::get('auto_blog_daily_count', 2),
            'auto_blog_window_start_hour' => (int) AdminSettings::get('auto_blog_window_start_hour', 6),
            'auto_blog_window_end_hour' => (int) AdminSettings::get('auto_blog_window_end_hour', 18),
            'auto_blog_variant_best' => (bool) AdminSettings::get('auto_blog_variant_best', true),
            'auto_blog_variant_guide' => (bool) AdminSettings::get('auto_blog_variant_guide', true),
            'auto_blog_variant_comparison' => (bool) AdminSettings::get('auto_blog_variant_comparison', true),
            'auto_blog_brand_intro_enabled' => (bool) AdminSettings::get('auto_blog_brand_intro_enabled', true),
            'seo_title_suffix' => (string) AdminSettings::get('seo_title_suffix', '- ' . config('app.name')),
            'seo_meta_description_default' => (string) AdminSettings::get('seo_meta_description_default', 'Best coupons, deals and store reviews.'),
            'seo_og_image_default' => (string) AdminSettings::get('seo_og_image_default', ''),
            'auto_post_api_url' => (string) AdminSettings::get('auto_post_api_url', 'http://localhost:8000/api/coupons/sync'),
            'auto_post_bearer_token' => AdminSettings::getEncrypted('auto_post_bearer_token') ? '********' : '',
            'auto_post_platforms' => AutoPostSettings::platforms(),
            'auto_post_type' => AutoPostSettings::type(),
            'auto_post_allow_repeat' => AutoPostSettings::allowRepeat(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Traffic API (Apify)')
                    ->description('Dùng cho trang lọc traffic theo domain.')
                    ->schema([
                        TextInput::make('apify_token')
                            ->label('Apify token')
                            ->password()
                            ->revealable()
                            ->helperText('Nhập token mới để lưu. Nếu để "********" thì giữ token hiện tại.')
                            ->maxLength(255),
                        TextInput::make('apify_actor_id')
                            ->label('Actor ID')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('traffic_threshold_default')
                            ->label('Ngưỡng traffic mặc định')
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                    ])
                    ->columns(3),
                Section::make('AI Content (Gemini)')
                    ->description('Dùng cho tạo blog AI trong admin và cron.')
                    ->schema([
                        TextInput::make('gemini_api_key')
                            ->label('Gemini API key')
                            ->password()
                            ->revealable()
                            ->helperText('Nhập key mới để lưu. Nếu để "********" thì giữ key hiện tại.')
                            ->maxLength(255),
                        TextInput::make('gemini_model')
                            ->label('Gemini model')
                            ->required()
                            ->maxLength(120),
                        TextInput::make('gemini_timeout')
                            ->label('Timeout (giây)')
                            ->numeric()
                            ->minValue(5)
                            ->required(),
                    ])
                    ->columns(3),
                Section::make('Auto Blog')
                    ->description('Thiết lập tạo blog tự động theo ngày, khung giờ và variant.')
                    ->schema([
                        Toggle::make('auto_blog_enabled')
                            ->label('Bật Auto Blog')
                            ->inline(false),
                        TextInput::make('auto_blog_daily_count')
                            ->label('Số bài/ngày')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                        TextInput::make('auto_blog_window_start_hour')
                            ->label('Giờ bắt đầu (0-23)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(23)
                            ->required(),
                        TextInput::make('auto_blog_window_end_hour')
                            ->label('Giờ kết thúc (0-23)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(23)
                            ->required(),
                        Toggle::make('auto_blog_variant_best')
                            ->label('Bật variant: Bài viết lựa chọn tốt nhất')
                            ->inline(false),
                        Toggle::make('auto_blog_variant_guide')
                            ->label('Bật variant: Bài viết hướng dẫn')
                            ->inline(false),
                        Toggle::make('auto_blog_variant_comparison')
                            ->label('Bật variant: Bài viết so sánh')
                            ->inline(false),
                        Toggle::make('auto_blog_brand_intro_enabled')
                            ->label('Bật variant:Bài viết về cửa hàng đang có')
                            ->inline(false),
                    ])
                    ->columns(3),
                Section::make('Tự động đăng bài')
                    ->description('Cấu hình API đăng bài hàng loạt từ tab Chiến dịch.')
                    ->schema([
                        TextInput::make('auto_post_api_url')
                            ->label('API URL')
                            ->url()
                            ->required()
                            ->default('http://localhost:8000/api/coupons/sync')
                            ->maxLength(500),
                        TextInput::make('auto_post_bearer_token')
                            ->label('Bearer Token')
                            ->password()
                            ->revealable()
                            ->helperText('Nhập token mới để lưu. Nếu để "********" thì giữ token hiện tại.')
                            ->maxLength(500),
                        CheckboxList::make('auto_post_platforms')
                            ->label('Nền tảng đăng bài')
                            ->options(AutoPostSettings::PLATFORM_OPTIONS)
                            ->columns(3)
                            ->required(),
                        Select::make('auto_post_type')
                            ->label('Loại nội dung (type)')
                            ->options(AutoPostSettings::TYPE_OPTIONS)
                            ->required()
                            ->default('video'),
                        Toggle::make('auto_post_allow_repeat')
                            ->label('Đăng lặp lại')
                            ->helperText('Bật: đăng cả domain đã đăng trước đó. Tắt: bỏ qua domain đã có trong lịch sử đăng.')
                            ->inline(false),
                    ])
                    ->columns(2),
                Section::make('SEO mặc định')
                    ->description('Áp dụng cho các trang dùng layout chính.')
                    ->schema([
                        TextInput::make('seo_title_suffix')
                            ->label('Title suffix')
                            ->helperText('Ví dụ: - ReviewHays')
                            ->maxLength(120),
                        TextInput::make('seo_meta_description_default')
                            ->label('Meta description fallback')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('seo_og_image_default')
                            ->label('OpenGraph image mặc định (URL)')
                            ->url()
                            ->maxLength(500),
                    ])
                    ->columns(1),
                Section::make('Thiết lập chung')
                    ->schema([
                        TextInput::make('site_contact_email')
                            ->label('Email liên hệ hiển thị ở trang Contact')
                            ->email()
                            ->required()
                            ->maxLength(255),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Lưu cài đặt')
                ->action('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $apifyToken = trim((string) ($data['apify_token'] ?? ''));
        if ($apifyToken !== '' && $apifyToken !== '********') {
            AdminSettings::setEncrypted('apify_token', $apifyToken);
        }

        $geminiApiKey = trim((string) ($data['gemini_api_key'] ?? ''));
        if ($geminiApiKey !== '' && $geminiApiKey !== '********') {
            AdminSettings::setEncrypted('gemini_api_key', $geminiApiKey);
        }

        AdminSettings::set('apify_actor_id', trim((string) ($data['apify_actor_id'] ?? 'aqPbs3KeH9aD8b22w')));
        AdminSettings::set('traffic_threshold_default', (int) ($data['traffic_threshold_default'] ?? 100000));
        AdminSettings::set('gemini_model', trim((string) ($data['gemini_model'] ?? config('gemini.model', 'gemini-1.5-flash-latest'))));
        AdminSettings::set('gemini_timeout', max(5, (int) ($data['gemini_timeout'] ?? config('gemini.timeout', 60))));
        AdminSettings::set('site_contact_email', trim((string) ($data['site_contact_email'] ?? config('mail.from.address', 'contact@example.com'))));
        AdminSettings::set('auto_blog_enabled', (bool) ($data['auto_blog_enabled'] ?? true));
        AdminSettings::set('auto_blog_daily_count', max(1, (int) ($data['auto_blog_daily_count'] ?? 2)));
        AdminSettings::set('auto_blog_window_start_hour', max(0, min(23, (int) ($data['auto_blog_window_start_hour'] ?? 6))));
        AdminSettings::set('auto_blog_window_end_hour', max(0, min(23, (int) ($data['auto_blog_window_end_hour'] ?? 18))));
        AdminSettings::set('auto_blog_variant_best', (bool) ($data['auto_blog_variant_best'] ?? true));
        AdminSettings::set('auto_blog_variant_guide', (bool) ($data['auto_blog_variant_guide'] ?? true));
        AdminSettings::set('auto_blog_variant_comparison', (bool) ($data['auto_blog_variant_comparison'] ?? true));
        AdminSettings::set('auto_blog_brand_intro_enabled', (bool) ($data['auto_blog_brand_intro_enabled'] ?? true));
        AdminSettings::set('seo_title_suffix', trim((string) ($data['seo_title_suffix'] ?? ('- ' . config('app.name')))));
        AdminSettings::set('seo_meta_description_default', trim((string) ($data['seo_meta_description_default'] ?? 'Best coupons, deals and store reviews.')));
        AdminSettings::set('seo_og_image_default', trim((string) ($data['seo_og_image_default'] ?? '')));

        $bearerToken = trim((string) ($data['auto_post_bearer_token'] ?? ''));
        if ($bearerToken !== '' && $bearerToken !== '********') {
            AdminSettings::setEncrypted('auto_post_bearer_token', $bearerToken);
        }

        AdminSettings::set('auto_post_api_url', trim((string) ($data['auto_post_api_url'] ?? 'http://localhost:8000/api/coupons/sync')));
        AdminSettings::set('auto_post_platforms', array_values((array) ($data['auto_post_platforms'] ?? ['blog', 'instagram', 'facebook'])));
        AdminSettings::set('auto_post_type', trim((string) ($data['auto_post_type'] ?? 'video')));
        AdminSettings::set('auto_post_allow_repeat', (bool) ($data['auto_post_allow_repeat'] ?? false));

        $this->mount();

        Notification::make()
            ->title('Đã lưu cài đặt hệ thống')
            ->success()
            ->send();
    }
}
