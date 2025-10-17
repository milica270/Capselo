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

class FeedController extends Controller
{
    public function feed()
    {
        $user = Auth::user();

       $friendIds = Friendship::where(function ($q) use ($user) {
        $q->where('user_id', $user->id)
          ->orWhere('friend_id', $user->id);
    })
    ->where('accepted', 1) // ✅ only accepted friendships
    ->get(['user_id', 'friend_id'])
    ->flatMap(function ($friendship) use ($user) {
        // Return the ID of the other user in each friendship
        return [
            $friendship->user_id == $user->id
                ? $friendship->friend_id
                : $friendship->user_id
        ];
    })
    ->unique()
    ->values();
        // 2️⃣ Get all capsules where:
        //   - Owner is a friend OR
        //   - Friend is in capsule_user
        //   - AND visible_to = 'friends' or 'everyone'
        $capsules = Capsule::whereIn('visible_to', ['friends', 'everyone'])
            ->where(function ($query) use ($friendIds) {
                $query->whereIn('owner_id', $friendIds)
                      ->orWhereHas('users', function ($sub) use ($friendIds) {
                          $sub->whereIn('users.id', $friendIds);
                      });
            })
            ->with(['owner', 'users', 'images'])
            ->orderByDesc('created_at')
            ->get()
            ->filter(fn ($capsule) => $capsule->is_ready())
            ->values(); 

        return Inertia::render('Feed', [
            'user' => $user,
            'friends' => $friendIds,
            'capsules' => $capsules,
        ]);
    }
}
