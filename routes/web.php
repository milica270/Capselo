<?php
use Inertia\Inertia;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\CapsuleController;
use App\Models\Friendship;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

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

Route::put('/user/update', [DashboardController::class, 'update_user'])->name('users.update');

Route::get('/feed', function () {
    return Inertia::render('Feed');
})->name('feed');

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


});

Route::delete('/account/delete', [AuthController::class, 'delete_user'])->middleware('auth')->name('account.delete');

Route::get('/verification-status', function () {
    return ['verified' => auth()->user()?->email_verified_at !== null];
})->middleware('auth')->name('verification.status');