<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Friendship;

class DashboardController extends Controller
{
    public function dashboard() {
        return Inertia::render('Dashboard', [
        'user' => Auth::user(),
        'users' => User::all(),
        'friendships' => Friendship::all(),
    ]);
    }

    public function edit_profile() {
        return Inertia::render('EditProfile', [
        'user' => Auth::user(),
    ]);
    }

    public function update_user(Request $request) {
        $user = Auth::user(); // or User::find($id) if updating another user

    
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'lastName' => 'required|string|max:255',
        'birthdate' => 'required|date',
        'country' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'role' => 'required|in:school,job',
        'schoolType' => 'nullable|in:elementary,highschool,university',
        'jobType' => 'nullable|string|max:255',
    ]);

    // ✅ 2. Handle avatar upload if present
    if ($request->hasFile('avatar')) {
        // delete old avatar if exists
        if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
            Storage::delete('public/' . $user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $validated['avatar'] = $path;
    }

    // ✅ 3. Update user fields
    $user->update($validated);

    // ✅ 4. Redirect or respond
    return Inertia::location(route('dashboard'));
    }
}
