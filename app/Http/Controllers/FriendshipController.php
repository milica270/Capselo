<?php

// app/Http/Controllers/FriendshipController.php
namespace App\Http\Controllers;

use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\User;

class FriendshipController extends Controller
{

    public function show() {
        return Inertia::render('Friends', [
        'users' => User::all(),
        'friendships' => Friendship::all(),
    ]);
    }
    public function sendRequest($friendId)
    {
        $userId = Auth::id();

        // Prevent duplicate or self requests
        if ($userId == $friendId || Friendship::where('user_id', $userId)->where('friend_id', $friendId)->exists()) {
            return back()->with('error', 'Request already sent.');
        }

        Friendship::create([
            'user_id' => $userId,
            'friend_id' => $friendId,
        ]);

        return back()->with('success', 'Friend request sent!');
    }

    public function acceptRequest($id)
    {
        $friendship = Friendship::findOrFail($id);

        if ($friendship->friend_id == Auth::id()) {
            $friendship->update(['accepted' => true]);
            return back()->with('success', 'Friend request accepted!');
        }

        abort(403);
    }

    public function declineRequest($id)
    {
        $friendship = Friendship::findOrFail($id);

        if ($friendship->friend_id == Auth::id()) {
            $friendship->delete();
            return back()->with('success', 'Friend request declined.');
        }

        abort(403);
    }

    public function removeFriend($friendId)
    {
        $userId = Auth::id();

        Friendship::where(function($query) use ($userId, $friendId) {
            $query->where('user_id', $userId)->where('friend_id', $friendId);
        })->orWhere(function($query) use ($userId, $friendId) {
            $query->where('user_id', $friendId)->where('friend_id', $userId);
        })->delete();

        return back()->with('success', 'Friend removed.');
    }
}

