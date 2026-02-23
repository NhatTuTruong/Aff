<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\LandingPageCheck;
use Illuminate\Console\Command;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CheckLandingPagesCommand extends Command
{
    protected $signature = 'health:check-landing
        {--user= : Only check campaigns belonging to this user_id}
        {--limit=200 : Max campaigns to check}
        {--only-errors : Only persist non-200 results}
        {--timeout=10 : Request timeout seconds (for internal handling only)}
    ';

    protected $description = 'Check landing page (coupon) URLs and store status codes for admin reporting.';

    public function handle(HttpKernel $kernel): int
    {
        $userId = $this->option('user') ? (int) $this->option('user') : null;
        $limit = max(1, (int) $this->option('limit'));
        $onlyErrors = (bool) $this->option('only-errors');

        $campaigns = Campaign::query()
            ->select(['id', 'slug', 'status', 'brand_id', 'created_at'])
            ->with(['brand:id,user_id,deleted_at'])
            ->whereHas('brand', function ($q) use ($userId) {
                $q->whereNull('deleted_at')
                    ->when($userId, fn ($qq) => $qq->where('user_id', $userId))
                    ->whereExists(function ($sub) {
                        $sub->selectRaw(1)
                            ->from('users')
                            ->whereColumn('users.id', 'brands.user_id');
                    });
            })
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();

        $checkedAt = Carbon::now();

        $ok = 0;
        $bad = 0;

        foreach ($campaigns as $campaign) {
            $slug = (string) ($campaign->slug ?? '');
            [$code, $slugPart] = array_pad(explode('/', $slug, 2), 2, '');

            $statusCode = 0;
            $error = null;
            $urlPath = '';

            if (! preg_match('/^[0-9]{5}$/', $code) || ! preg_match('/^[a-z0-9-]+$/', $slugPart)) {
                $statusCode = 0;
                $error = 'invalid_slug_format';
                $urlPath = "/visit/{$code}/{$slugPart}";
            } else {
                $urlPath = "/visit/{$code}/{$slugPart}";

                try {
                    $request = Request::create($urlPath, 'GET', ['health_check' => 1], server: [
                        'HTTP_HOST' => parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost',
                        'HTTP_X_HEALTH_CHECK' => '1',
                        'HTTP_ACCEPT' => 'text/html',
                    ]);

                    $response = $kernel->handle($request);
                    $statusCode = (int) $response->getStatusCode();
                    $kernel->terminate($request, $response);
                } catch (\Throwable $e) {
                    $statusCode = 500;
                    $error = class_basename($e);
                }
            }

            if ($statusCode === 200) {
                $ok++;
            } else {
                $bad++;
            }

            if ($onlyErrors && $statusCode === 200) {
                // Optionally keep old errors until overwritten by a future run
                continue;
            }

            LandingPageCheck::query()->updateOrCreate(
                ['campaign_id' => $campaign->id],
                [
                    'user_id' => $campaign->brand?->user_id,
                    'url_path' => $urlPath,
                    'status_code' => $statusCode,
                    'error' => $error,
                    'checked_at' => $checkedAt,
                ],
            );
        }

        $this->info("Checked: {$campaigns->count()} | OK: {$ok} | Issues: {$bad}");

        return self::SUCCESS;
    }
}

