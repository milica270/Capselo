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



    // Get IDs of accepted friends
    $friendIds = Friendship::where(function ($q) use ($user) {
        $q->where('user_id', $user->id)
          ->orWhere('friend_id', $user->id);
    })
    ->where('accepted', 1)
    ->get(['user_id', 'friend_id'])
    ->flatMap(function ($friendship) use ($user) {
        return [
            $friendship->user_id == $user->id
                ? $friendship->friend_id
                : $friendship->user_id
        ];
    })
    ->unique()
    ->values();

    // Get capsules according to visibility rules
    $capsules = Capsule::with(['owner', 'users', 'images'])
        ->where(function ($query) use ($user, $friendIds) {
            $query
                ->where(function ($q) use ($user) {
                    $q->where('owner_id', $user->id);
                })
                ->orWhere(function ($q) use ($friendIds) {
                    $q->where('visible_to', 'friends')
                      ->whereIn('owner_id', $friendIds);
                })
                ->orWhere(function ($q) {
                    $q->where('visible_to', 'everyone');
                });
        })
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
