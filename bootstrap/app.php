<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'org.access' => \App\Http\Middleware\OrganizationAccess::class,
        ]);

        // Safety net middleware: trigger email review lazy di public routes
        $middleware->web(prepend: [
            \App\Http\Middleware\TriggerPendingReviewEmails::class,
        ]);

        // Mengecualikan route webhook Midtrans dari blokir CSRF
        $middleware->validateCsrfTokens(except: [
            '/midtrans/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function (Schedule $schedule): void {
        // Kirim email review harian untuk event yang sudah lewat 24 jam
        // Setiap hari jam 09:00 pagi
        $schedule->command('reviews:send-daily-invitations')
                 ->dailyAt('09:00')
                 ->withoutOverlapping()
                 ->onOneServer();
    })->create();
