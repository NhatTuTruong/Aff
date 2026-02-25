<?php
 
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Simple health check endpoint (no tracking)
Route::get('/health', function () {
    $status = [
        'app' => 'ok',
        'timestamp' => now()->toIso8601String(),
    ];

    try {
        DB::connection()->getPdo();
        $status['db'] = 'ok';
    } catch (\Throwable $e) {
        $status['db'] = 'fail';
    }

    return response()->json($status, $status['db'] === 'ok' ? 200 : 500);
})->name('health');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/blog', [App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blog.show')->where('slug', '[a-z0-9\-]+');

// /login -> trang đăng nhập Filament (v3)
Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

// Landing Pages - Format: /visit/{user_code}/{slug}
Route::get('/visit/{userCode}/{slug}', [App\Http\Controllers\LandingPageController::class, 'show'])
    ->name('landing.show')
    ->where(['userCode' => '[0-9]{5}', 'slug' => '[a-z0-9-]+']);

// Click Tracking & Redirect - Format: /out/{user_code}/{slug}
Route::get('/out/{userCode}/{slug}', [App\Http\Controllers\ClickTrackingController::class, 'redirect'])
    ->name('click.redirect')
    ->where(['userCode' => '[0-9]{5}', 'slug' => '[a-z0-9-]+']);

// Analytics API
Route::post('/api/track-page-view/{pageView}', [App\Http\Controllers\AnalyticsController::class, 'updatePageView'])
    ->middleware('web')
    ->name('analytics.update-page-view');

// Legal Pages
Route::get('/about', function () {
    return view('legal.about');
})->name('legal.about');

Route::get('/contact', function () {
    return view('legal.contact');
})->name('legal.contact');

Route::get('/privacy', function () {
    return view('legal.privacy');
})->name('legal.privacy');

Route::get('/affiliate-disclosure', function () {
    return view('legal.affiliate');
})->name('legal.affiliate');

Route::get('/terms', function () {
    return view('legal.terms');
})->name('legal.terms');

Route::get('/deals', [App\Http\Controllers\DealsController::class, 'index'])->name('deals.index');

// Image upload for RichEditor paste
Route::post('/admin/campaigns/upload-image', [App\Http\Controllers\ImageUploadController::class, 'upload'])
    ->middleware('web')
    ->name('campaigns.upload-image');

// Tải CSV lỗi import (custom route - xử lý import có user_id null)
Route::get('/admin/imports/{import}/failed-rows/download', [App\Http\Controllers\DownloadImportFailedRowsCsvController::class, '__invoke'])
    ->middleware('web')
    ->name('admin.imports.failed-rows.download');

// Test authentication debug route (temporary)
Route::get('/test-auth', [App\Http\Controllers\TestAuthController::class, 'testAuth'])
    ->middleware(['web', 'auth'])
    ->name('test.auth');

// Test notification - gửi thông báo vào chuông (cần đăng nhập admin)
Route::get('/test-notification', function () {
    $user = auth()->user();
    if (! $user) {
        return redirect('/admin/login')->with('error', 'Vui lòng đăng nhập trước.');
    }

    \Filament\Notifications\Notification::make()
        ->title('Test thông báo thành công')
        ->body('Chuông thông báo đang hoạt động! Nếu bạn thấy thông báo này thì mọi thứ ổn.')
        ->success()
        ->icon('heroicon-o-bell')
        ->sendToDatabase($user);

    return redirect('/admin')->with('status', 'Đã gửi thông báo test. Kiểm tra icon chuông góc trên bên phải.');
})->middleware(['web', 'auth'])->name('test.notification');

