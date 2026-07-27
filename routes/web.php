<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OrganizationRegisterController;
use App\Http\Controllers\Organization\AuthController as OrgAuthController;
use App\Http\Controllers\Organization\DashboardController as OrgDashboardController;
use App\Http\Controllers\SmartLoginController;
use App\Http\Controllers\Organization\EventController as OrgEventController;
use App\Http\Controllers\Organization\AnalyticsController as OrgAnalyticsController;
use App\Http\Controllers\Organization\PayoutController as OrgPayoutController;
use App\Http\Controllers\Organization\StaffController as OrgStaffController;
use App\Http\Controllers\Admin\TenantController as AdminTenantController;
use App\Http\Controllers\Admin\PayoutApprovalController as AdminPayoutApprovalController;
use App\Http\Controllers\Admin\GlobalAnalyticsController as AdminGlobalAnalyticsController;
use App\Http\Controllers\Admin\CommissionController as AdminCommissionController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\TransactionController;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

Route::get('/checkout', [EventController::class, 'checkout'])->name('checkout');

Route::get('/checkout/{event}', [CheckoutController::class, 'create'])
    ->name('checkout.create');

Route::post('/checkout/{event}', [CheckoutController::class, 'store'])
    ->name('checkout.store');

Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])
    ->name('checkout.payment');

Route::get('/success/{order_id}', [CheckoutController::class, 'success'])
    ->name('checkout.success');

Route::get('/my-ticket', [TicketController::class, 'index'])->name('ticket');

// Review Routes
Route::get('/events/{event}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('/events/{event}/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
Route::post('/events/{event}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
Route::get('/my-reviews', [ReviewController::class, 'myReviews'])->name('reviews.my');
Route::get('/events/{event}/rating-stats', [ReviewController::class, 'getRatingStats'])->name('rating.stats');
Route::get('/events/{event}/review-status', [ReviewController::class, 'getReviewStatus'])->name('review.status');

// Public review form (via email token, no login required)
Route::get('/rate/{order_id}', [ReviewController::class, 'showPublicForm'])->name('review.public');
Route::post('/rate/{order_id}', [ReviewController::class, 'submitPublic'])->name('review.public.submit');

Route::post('/midtrans/callback', [\App\Http\Controllers\MidtransWebhookController::class, 'handle']);

// Google SSO (Laravel Socialite)
Route::get('/auth/google',          [SocialAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'callback'])->name('auth.google.callback');

// =============================================
// MULTI-TENANT: PENDAFTARAN KEPANITIAAN
// =============================================
Route::get('/jadi-penyelenggara',  [OrganizationRegisterController::class, 'showForm'])->name('organization.register');
Route::post('/jadi-penyelenggara', [OrganizationRegisterController::class, 'register'])->name('organization.register.submit');
Route::get('/pendaftaran-diterima', [OrganizationRegisterController::class, 'pending'])->name('organization.pending');

// Katalog kepanitiaan (public) -- redirect to unified login (removed public katalog page)
Route::get('/panitia', function () {
    return redirect()->route('login');
})->name('panitia.index');
Route::get('/panitia/{slug}', [App\Http\Controllers\PublicOrganizationController::class, 'show'])->name('panitia.show');

// =============================================
// MULTI-TENANT: PANITIA LOGIN & DASHBOARD
// =============================================

// Global logout (untuk semua role)
Route::post('/logout', [SmartLoginController::class, 'logout'])->name('logout');

Route::prefix('panitia/{slug}')->name('panitia.')->group(function () {
    // Logout (any user)
    Route::post('/logout', [OrgAuthController::class, 'logout'])->name('logout');

    // Protected
    Route::middleware(['auth', 'org.access'])->group(function () {
        Route::get('/dashboard', [OrgDashboardController::class, 'index'])->name('dashboard');
        Route::get('/events', [OrgEventController::class, 'index'])->name('events.index');
        Route::get('/events/create', [OrgEventController::class, 'create'])->name('events.create');
        Route::post('/events', [OrgEventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [OrgEventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}', [OrgEventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [OrgEventController::class, 'destroy'])->name('events.destroy');
        Route::get('/analytics', [OrgAnalyticsController::class, 'index'])->name('analytics');
        Route::get('/payouts', [OrgPayoutController::class, 'index'])->name('payouts');
        Route::post('/payouts', [OrgPayoutController::class, 'store'])->name('payouts.store');
        Route::get('/staff', [OrgStaffController::class, 'index'])->name('staff');
        Route::post('/staff/invite', [OrgStaffController::class, 'invite'])->name('staff.invite');
        Route::delete('/staff/{user}', [OrgStaffController::class, 'destroy'])->name('staff.destroy');
    });
});

// Universal Smart Login (gantikan /panitia/{slug}/login)
Route::get('/login',  [SmartLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [SmartLoginController::class, 'login'])->name('login.post');

// Dev-only: force logout (bersihkan session, abaikan redirect)
Route::get('/force-logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('force.logout');

Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware(['auth', 'admin'])->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/events', [AdminEventController::class, 'index'])
            ->name('events.index');

        Route::get('/events/create', [AdminEventController::class, 'create'])
            ->name('events.create');

        Route::post('/events', [AdminEventController::class, 'store'])
            ->name('events.store');

        Route::get('/events/{event}/edit', [AdminEventController::class, 'edit'])
            ->name('events.edit');

        Route::put('/events/{event}', [AdminEventController::class, 'update'])
            ->name('events.update');

        Route::delete('/events/{event}', [AdminEventController::class, 'destroy'])
            ->name('events.destroy');

        Route::get('/transactions', [TransactionController::class, 'index'])
            ->name('transactions.index');

        // CATEGORY ROUTES
        Route::get('/categories', [CategoryController::class, 'index'])
            ->name('categories.index');

        Route::get('/categories/create', [CategoryController::class, 'create'])
            ->name('categories.create');

        Route::post('/categories', [CategoryController::class, 'store'])
            ->name('categories.store');

        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])
            ->name('categories.edit');

        Route::put('/categories/{category}', [CategoryController::class, 'update'])
            ->name('categories.update');

        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
            ->name('categories.destroy');

        // PARTNER ROUTES
        // READ
        Route::get('/partners', [PartnerController::class, 'index'])
            ->name('partners.index');

        // CREATE FORM
        Route::get('/partners/create', [PartnerController::class, 'create'])
            ->name('partners.create');

        // STORE DATA
        Route::post('/partners', [PartnerController::class, 'store'])
            ->name('partners.store');

        // EDIT FORM
        Route::get('/partners/{partner}/edit', [PartnerController::class, 'edit'])
            ->name('partners.edit');

        // UPDATE DATA
        Route::put('/partners/{partner}', [PartnerController::class, 'update'])
            ->name('partners.update');

        // DELETE DATA
        Route::delete('/partners/{partner}', [PartnerController::class, 'destroy'])
            ->name('partners.destroy');

        Route::get('transactions', [\App\Http\Controllers\Admin\TransactionController::class, 'index'])
            ->name('transactions.index');

        // MULTI-TENANT: Tenant Management (URL langsung, tanpa /tenants prefix)
        Route::get('/tenants', [AdminTenantController::class, 'index'])->name('tenants.index');
        Route::get('/pending', [AdminTenantController::class, 'pending'])->name('tenants.pending');
        Route::get('/tenants/{id}', [AdminTenantController::class, 'show'])->name('tenants.show');
        Route::post('/tenants/{id}/approve', [AdminTenantController::class, 'approve'])->name('tenants.approve');
        Route::post('/tenants/{id}/reject', [AdminTenantController::class, 'reject'])->name('tenants.reject');
        Route::post('/tenants/{id}/suspend', [AdminTenantController::class, 'suspend'])->name('tenants.suspend');
        Route::post('/tenants/{id}/activate', [AdminTenantController::class, 'activate'])->name('tenants.activate');

        // MULTI-TENANT: Payout Approval
        Route::get('/payouts', [AdminPayoutApprovalController::class, 'index'])->name('payouts.index');
        Route::post('/payouts/{id}/approve', [AdminPayoutApprovalController::class, 'approve'])->name('payouts.approve');
        Route::post('/payouts/{id}/reject', [AdminPayoutApprovalController::class, 'reject'])->name('payouts.reject');

        // MULTI-TENANT: Global Analytics & Komisi
        Route::get('/analytics', [AdminGlobalAnalyticsController::class, 'index'])->name('analytics');
        Route::get('/komisi', [AdminCommissionController::class, 'index'])->name('komisi');
        Route::post('/komisi/{id}', [AdminCommissionController::class, 'update'])->name('komisi.update');

    });

});