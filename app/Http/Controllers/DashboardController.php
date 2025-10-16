<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Friendship;
use App\Models\Capsule;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
  public function dashboard()
{
    $user = Auth::user();

    $ownedDrafts = Capsule::where('capsules.owner_id', $user->id)
        ->where(function ($q) {
            $q->where('capsules.ready', 0) // owner's ready flag
              ->orWhereHas('users', function ($q2) {
                  $q2->where('capsule_user.ready', 0); // any invited user not ready
              });
        })
        ->with(['users' => function ($q) {
            $q->withPivot('ready');
        }, 'images', 'owner'])
        ->get();

    // Invited capsules (drafts = not everyone ready)
    $invitedDrafts = $user->invitedCapsules()
        ->where(function ($q) {
            $q->where('capsules.ready', 0) // owner not ready
              ->orWhereHas('users', function ($q2) {
                  $q2->where('capsule_user.ready', 0); // invited not ready
              });
        })
        ->with(['users' => function ($q) {
            $q->withPivot('ready');
        }, 'images', 'owner'])
        ->get();


        // --- OWNED READY CAPSULES (everyone ready) ---
    $ownedReadyCapsules = Capsule::where('capsules.owner_id', $user->id)
        ->where('capsules.ready', 1)
        ->whereDoesntHave('users', function ($q) {
            $q->where('capsule_user.ready', 0);
        })
        ->with(['users' => function ($q) {
            $q->withPivot('ready');
        }, 'images', 'owner'])
        ->get();

    // --- INVITED READY CAPSULES (everyone ready) ---
    $invitedReadyCapsules = $user->invitedCapsules()
        ->where('capsules.ready', 1)
        ->whereDoesntHave('users', function ($q) {
            $q->where('capsule_user.ready', 0);
        })
        ->with(['users' => function ($q) {
            $q->withPivot('ready');
        }, 'images', 'owner'])
        ->get();


    return Inertia::render('Dashboard', [
        'user' => $user,
        'users' => User::all(),
        'friendships' => Friendship::all(),
        'ownedCapsules' => $ownedDrafts,
        'invitedCapsules' => $invitedDrafts,
        'ownedReadyCapsules' => $ownedReadyCapsules,
        'invitedReadyCapsules' => $invitedReadyCapsules,
        
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

    public function edit_profile(){
        $user = Auth::user();
        return Inertia::render('EditProfile', [
            'user' => $user,
        ]);
    }
}
