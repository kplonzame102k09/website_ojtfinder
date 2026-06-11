<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NewsfeedController;
use App\Http\Controllers\CompanyRegisterController;
use App\Http\Controllers\CompanyDashboardController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\UserSearchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\LegalController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\StudentRequirementController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Company;
use App\Models\User;
/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('home'); 
    }
    return redirect()->route('home'); 
})->name('landing');

//==== Authentication Routes ====//

//==== Login ====//
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

//==== Signup ====//
Route::get('/signup', [SignupController::class, 'showSignupForm'])->name('signup');
Route::post('/signup', [SignupController::class, 'signup']);

//==== Logout ====//
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
//===============================//


Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

//==== Company Management ====//
Route::get('/company/register', [CompanyRegisterController::class, 'create'])->name('company.register');
Route::post('/company/register', [CompanyRegisterController::class, 'store'])->name('company.register.store');
//============================//

//==== Role update ====//
Route::post('/update-role', [ProfileController::class, 'updateRole'])->name('user.updateRole')->middleware('auth');
//=====================//

//==== Payment Method ====//
//Route::post('/gcash/create', [PaymentMethodController::class, 'createGcash'])->name('gcash.create');
// Route::get('/payment_method', function () {
//         return view('pages.payment_method');
//     })->name('payment.method');

Route::get('public/sitemap.xml', function () {

    $sitemap = Sitemap::create();

    // ── Static Public Pages ──────────────────────────────────────────
    $sitemap->add(Url::create('/')
        ->setPriority(1.0)
        ->setChangeFrequency('daily'));

    $sitemap->add(Url::create('/login')
        ->setPriority(0.6)
        ->setChangeFrequency('monthly'));

    $sitemap->add(Url::create('/signup')
        ->setPriority(0.8)
        ->setChangeFrequency('monthly'));

    // ── Legal Pages ──────────────────────────────────────────────────
    $sitemap->add(Url::create('/legal/terms')
        ->setPriority(0.4)
        ->setChangeFrequency('yearly'));

    $sitemap->add(Url::create('/legal/privacy')
        ->setPriority(0.4)
        ->setChangeFrequency('yearly'));

    // ── Dynamic Company Pages ────────────────────────────────────────
    \App\Models\Company::all()->each(function ($company) use ($sitemap) {
        $sitemap->add(Url::create("/companies/{$company->id}")
            ->setLastModificationDate($company->updated_at)
            ->setPriority(0.9)
            ->setChangeFrequency('weekly'));
    });

    // ── Dynamic User Profile Pages ───────────────────────────────────
    \App\Models\User::whereNotNull('slug')
        ->where('role', '!=', 'admin') // exclude admin profile
        ->each(function ($user) use ($sitemap) {
            $sitemap->add(Url::create("/profile/{$user->slug}")
                ->setLastModificationDate($user->updated_at)
                ->setPriority(0.7)
                ->setChangeFrequency('weekly'));
        });

    return $sitemap->toResponse(request());

})->name('sitemap');
/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

//==== Admin Routes ====//
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.user.delete');
    Route::delete('/admin/companies/{company}', [AdminController::class, 'destroyCompany'])->name('admin.company.delete');
    Route::delete('/admin/posts/{post}', [AdminController::class, 'destroyPost'])->name('admin.post.delete');
Route::get('/company-file/{company}/{path}', function ($companyId, $path) {
    // 1. Authorization Check
    if (Auth::id() !== 1) {
        abort(403, 'Unauthorized');
    }

    // 2. Prevent path traversal
    if (str_contains($path, '..')) {
        abort(403, 'Invalid path');
    }

    // 3. InfinityFree specific pathing
    // We use base_path() to ensure we stay within the htdocs/laravel_core (or htdocs) jail
    $fullPath = base_path('storage/app/public/' . $path);

    // 4. File existence check
    if (!file_exists($fullPath)) {
        // Debugging tip: If it fails, uncomment the next line to see where it's looking
        return response()->json(['looking_at' => $fullPath]); 
        abort(404, 'File not found');
    }

    // 5. Return the file
    return response()->file($fullPath);

})->where('path', '.*')->name('company.file');
});
//=======================//

Route::middleware(['auth', 'verified', 'restrict.guest'])->group(function(){
    
    //==== User Search ====//
    Route::get('/users/search', [UserSearchController::class, 'search'])->name('users.search');

    //==== Profile & Social System ====//
    Route::get('/profile',[ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/{user:slug}', [ProfileController::class, 'show'])->name('profile.show');
    
    //==== This uses the slug to identify who you want to follow/unfollow ====//
    Route::post('/follow/{user:slug}', [FollowController::class, 'toggle'])->name('follow.toggle');
    
    //==== Newsfeed Routes ====//
    Route::get('/newsfeed', [NewsfeedController::class, 'index'])->name('newsfeed');
    Route::post('/newsfeed', [NewsfeedController::class, 'store'])->middleware('has.company')->name('newsfeed.store');
    Route::delete('/newsfeed/{post}', [NewsfeedController::class, 'destroy'])->name('newsfeed.destroy');
    
    //==== Liking logic ====//
    Route::post('/posts/{post}/like', [NewsfeedController::class, 'toggleLike'])->name('posts.like');

    //==== Commenting logic ====//
    Route::post('/posts/{post}/comments', [NewsfeedController::class, 'storeComment'])->name('newsfeed.comment');
    
    //==== Company Management ====//
    Route::get('/dashboard', [CompanyDashboardController::class, 'index'])->name('company_dashboard');
    Route::put('/company/logo', [CompanyDashboardController::class, 'updateLogo'])->name('company.logo.update');
    Route::put('/company/details', [CompanyDashboardController::class, 'updateDetails'])->name('company.details.update');
    Route::patch('/applications/{application}/status', [App\Http\Controllers\CompanyDashboardController::class, 'updateStatus'])->name('applications.update-status');
    
    //==== Messaging System ====//
    Route::get('/messages', [MessagesController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user:slug}', [MessagesController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user:slug}/send', [MessagesController::class, 'send'])->name('messages.send');
    
    //==== Notifications Group ====//
    Route::middleware('auth')->get('/notifications/counts', function () {
        return response()->json(['unreadNotifications' => auth()->user()->unreadNotifications()->count(),
            'unreadMessages' => auth()->user()->receivedMessages()->whereNull('read_at')->count(),
        ]);
    })->name('notifications.counts');
    Route::get('/notification', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'readAndRedirect'])->name('notifications.readAndRedirect');

    //Route::middleware(['auth'])->group(function () {
    Route::post('/applications/store', [ApplicationController::class, 'store'])->name('applications.store');
    //});
    
    //==== Image Display Route (for both profile pictures and company logos) ====//
    Route::get('/display-image/{path}', [ImageController::class, 'show'])->where('path', '.*')->name('image.display');
    
});
//==========================================================================//

//==== Student Requirements ====//
Route::get('/student/requirements/{requirement}', [StudentRequirementController::class, 'view'])->name('student.requirements.view')->middleware('auth');
Route::get('/student/requirements/{requirement}/download',[StudentRequirementController::class, 'download'])->name('student.requirements.download')->middleware('auth');
Route::post('/student/requirements/upload', [StudentRequirementController::class, 'store'])->name('student.requirements.upload')->middleware('auth');

//==== Password Reset Routes ====//
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

//==== Legal Pages (Terms of Service, Privacy Policy, etc.) ====//
Route::get('/legal/{type}', [LegalController::class, 'show'])->name('legal.show');

//==== The Notice (The page users see saying "Verify your email") ====//
Route::get('/email/verify', function () {
    return view('auth.verify_email');})->middleware('auth')->name('verification.notice');

//==== The Link (The route handled when they click the email link) ====//
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home');
    })->middleware(['auth', 'signed'])->name('verification.verify');

//==== Resending the email ====//
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');

    //Settings
Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
Route::put('/settings/update', [SettingsController::class, 'update'])->name('settings.update');
Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
