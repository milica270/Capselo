<?php
use Inertia\Inertia;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\CapsuleController;
use App\Http\Controllers\FeedController;
use App\Models\Friendship;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('/login', function() {
    return Inertia::render('Home'); 
})->name('login');


Route::middleware(['guest'])->group(function () {

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/store', [AuthController::class, 'store'])->name('store');
Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('authenticate');

});




Route::get('/email/verify', function () {
    return Inertia::render('VerifyEmail');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');




Route::middleware(['auth','verified'])->group(function () {

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
Route::get('/dashboard/edit_profile', [DashboardController::class, 'edit_profile'])->name('edit_profile');

Route::post('/user/update', [DashboardController::class, 'update_user'])->name('users.update');



Route::get('/feed', [FeedController::class, 'feed'])->name('feed');


Route::get('/premium', function () {
    return Inertia::render('Premium');
})->name('premium');



Route::get('/notifications', function () {
    return Inertia::render('Notifications');
})->name('notifications');


Route::post('/friend-request/{friendId}', [FriendshipController::class, 'sendRequest'])->name('friend.send');
Route::post('/friend-request/accept/{id}', [FriendshipController::class, 'acceptRequest'])->name('friend.accept');
Route::delete('/friend-request/decline/{id}', [FriendshipController::class, 'declineRequest'])->name('friend.decline');
Route::delete('/friend/remove/{friendId}', [FriendshipController::class, 'removeFriend'])->name('friend.remove');

Route::get('/dashboard/friends', [FriendshipController::class, 'show'])->name('friends');

Route::post('/capsules', [CapsuleController::class, 'store'])->name('capsules.store');


Route::delete('/capsules/{id}', [CapsuleController::class, 'destroy'])->name('capsules.destroy');
Route::delete('/capsules/{id}/leave', [CapsuleController::class, 'leave'])->name('capsules.leave');
Route::post('/capsules/{capsule}/add-image', [CapsuleController::class, 'addImage'])->name('capsules.addImage');
Route::delete('/capsules/image/{id}', [CapsuleController::class, 'deleteImage'])->name('capsules.deleteImage');

Route::put('/capsules/{id}/updateHashtags', [CapsuleController::class, 'updateHashtags'])
    ->name('capsules.updateHashtags');

Route::post('/capsules/{capsule}/toggle-ready', [CapsuleController::class, 'toggleReady'])->name('capsules.toggleReady');

Route::post('/capsules/{capsule}/invite-more', [CapsuleController::class, 'inviteMore'])
    ->name('capsules.inviteMore');

Route::delete('/capsules/{capsule}/remove-invitation/{user}', [CapsuleController::class, 'removeInvitation'])
    ->middleware('auth')
    ->name('capsules.removeInvitation');

Route::put('/capsules/{capsule}/visibility', [CapsuleController::class, 'updateVisibility'])
    ->name('capsules.updateVisibility');

Route::get('/user/streak', function () {
    $user = Auth::user();
    if (!$user) {
        return response()->json(['error' => 'Not authenticated'], 401);
    }

    $streakData = $user->streak();

    // ✅ update streak_days in DB for tracking purposes
    $user->update(['streak_days' => $streakData['count']]);

    return response()->json([
        'streak' => $streakData['count'],
        'urgent' => $streakData['urgent']
    ]);
})->name('user.streak');




Route::get('/calendar-status', function (\Illuminate\Http\Request $request) {
    $user = auth()->user();

    $month = $request->query('month', now()->month); // 1-12
    $year = $request->query('year', now()->year);

    $capsules = $user->ownedCapsules()
        ->where('created_at', '<=', now())
        ->get();

    $days = [];

foreach ($capsules as $capsule) {
    $day = $capsule->created_at->day;
    $month = $capsule->created_at->month;
    $year = $capsule->created_at->year;

    $key = sprintf('%04d-%02d-%02d', $year, $month, $day);

    // Assign true if ready, false if not
    $days[$key] = $capsule->is_ready();
}



    return response()->json($days);
});


});

Route::delete('/account/delete', [AuthController::class, 'delete_user'])->middleware('auth')->name('account.delete');

Route::get('/verification-status', function () {
    return ['verified' => auth()->user()?->email_verified_at !== null];
})->middleware('auth')->name('verification.status');