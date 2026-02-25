<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, Request $request) {
            // Production safety: never leak exception details in admin panel responses.
            if (app()->environment('production') && ($request->is('admin') || $request->is('admin/*'))) {
                $message = 'Đang có lỗi hệ thống. Vui lòng thử lại.';

                $isLivewire = (bool) $request->headers->get('X-Livewire');
                if ($request->expectsJson() || $request->ajax() || $isLivewire) {
                    return response()->json([
                        'message' => $message,
                    ], 500);
                }

                // For full-page (HTML) admin requests, redirect with a flash toast message
                // so the admin UI never shows a stacktrace/error page.
                $fallback = auth()->check() ? url('/admin') : url('/admin/login');
                $previous = $request->headers->get('referer');
                $target = ($previous && $previous !== $request->fullUrl()) ? $previous : $fallback;

                if (strtoupper($request->getMethod()) === 'GET') {
                    return redirect()->to($target)->with('admin_error_toast', $message);
                }

                return redirect()
                    ->to($target)
                    ->withInput($request->except(['password', 'current_password', 'password_confirmation']))
                    ->with('admin_error_toast', $message);
            }

            return null;
        });
    }
}

