<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Friendship;
use App\Models\User;

class FriendshipSeeder extends Seeder
{
    public function run(): void
    {
        $milicaId = 22; // Milica's user ID

        // Check if Milica exists
        if (!User::find($milicaId)) {
            $this->command->warn("⚠️ User with ID $milicaId (Milica) not found.");
            return;
        }

        // Get all users except Milica
        $allUsers = User::where('id', '!=', $milicaId)->get();

        // Get user IDs that already have any friendship with Milica
        $existingFriendIds = Friendship::where('user_id', $milicaId)
            ->orWhere('friend_id', $milicaId)
            ->pluck('user_id')
            ->merge(
                Friendship::where('user_id', $milicaId)
                    ->orWhere('friend_id', $milicaId)
                    ->pluck('friend_id')
            )
            ->unique()
            ->toArray();

        // Filter out users that already have a friendship with Milica
        $eligibleUsers = $allUsers->whereNotIn('id', $existingFriendIds);

        // Pick 5 random users to send Milica friend requests
        $randomUsers = $eligibleUsers->random(min(5, $eligibleUsers->count()));

        foreach ($randomUsers as $user) {
            Friendship::create([
                'user_id' => $user->id,   // 👈 sender
                'friend_id' => $milicaId, // 👈 receiver
                'accepted' => 0,          // pending
            ]);
        }

        $this->command->info('✅ Created pending friendship requests for Milica (ID 22)');
    }
}

