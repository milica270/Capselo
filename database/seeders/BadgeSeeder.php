<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('badges')->insert([
            // 🟢 Example: Consistency badges
            [
                'name' => 'First Beat',
                'icon' => 'badges/music/1.png',
                'category' => 'music',
                'description' => '1 capsule with #music or #song or #playlist',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Soundtrack of Me',
                'icon' => 'badges/music/2.png',
                'category' => 'music',
                'description' => '3 capsules with #music or #song or #playlist',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jam Session',
                'icon' => 'badges/music/3.png',
                'category' => 'music',
                'description' => '3 capsules with #instrument',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mood Matcher',
                'icon' => 'badges/music/4.png',
                'category' => 'music',
                'description' => '5 capsules with #goodvibes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Heartstrings',
                'icon' => 'badges/music/5.png',
                'category' => 'music',
                'description' => '5 capsules with #music and #heartbreak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Energy Mode',
                'icon' => 'badges/music/6.png',
                'category' => 'music',
                'description' => '7 capsules with #music and #energy',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rainy Day Soul',
                'icon' => 'badges/music/7.png',
                'category' => 'music',
                'description' => '3 capsules with #melancholy',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vibe Curator',
                'icon' => 'badges/music/8.png',
                'category' => 'music',
                'description' => '10 capsules with #music',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Melody of Thoughts',
                'icon' => 'badges/music/9.png',
                'category' => 'music',
                'description' => '5 capsules with #music and #study',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bedroom Artist',
                'icon' => 'badges/music/10.png',
                'category' => 'music',
                'description' => '5 capsules with #original and #music',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lyricist Soul',
                'icon' => 'badges/music/11.png',
                'category' => 'music',
                'description' => '7 capsules with #poetry',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Visual Harmony',
                'icon' => 'badges/music/12.png',
                'category' => 'music',
                'description' => '10 capsules with #emotion',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Creative Pulse',
                'icon' => 'badges/music/13.png',
                'category' => 'music',
                'description' => '7 capsules with #creativity',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Studio Mode',
                'icon' => 'badges/music/14.png',
                'category' => 'music',
                'description' => '3 capsules with #passion and #music',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Valentine Vibes',
                'icon' => 'badges/music/15.png',
                'category' => 'music',
                'description' => '8 capsules with #music and #love',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Summer Playlist',
                'icon' => 'badges/music/16.png',
                'category' => 'music',
                'description' => '10 capsules with #music posted during summer months',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Autumn Echoes',
                'icon' => 'badges/music/17.png',
                'category' => 'music',
                'description' => '10 capsules with #music posted during autumn months',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Winter Lo-Fi',
                'icon' => 'badges/music/18.png',
                'category' => 'music',
                'description' => '15 capsules with #music posted during winter months',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Full Moon Mood',
                'icon' => 'badges/music/19.png',
                'category' => 'music',
                'description' => '3 capsules with #music and #night',
                'created_at' => now(),
                'updated_at' => now(),
            ],
           
            [
                'name' => 'Shared Playlist',
                'icon' => 'badges/music/20.png',
                'category' => 'music',
                'description' => '5 capsules with #music posted with another user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Memory Song',
                'icon' => 'badges/music/21.png',
                'category' => 'music',
                'description' => '3 capsules with #music and #memory',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Late Night Listener',
                'icon' => 'badges/music/22.png',
                'category' => 'music',
                'description' => '10 capsules with #music and #night',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sound of Growth',
                'icon' => 'badges/music/23.png',
                'category' => 'music',
                'description' => '10 capsules with #music and #personalgrowth',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Harmony Within',
                'icon' => 'badges/music/24.png',
                'category' => 'music',
                'description' => '3 capsules with #harmony',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rhythm Keeper',
                'icon' => 'badges/music/25.png',
                'category' => 'music',
                'description' => '5 capsules with #harmony',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Emotional Composer',
                'icon' => 'badges/music/26.png',
                'category' => 'music',
                'description' => '10 capsules with #emotion',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Universal Language',
                'icon' => 'badges/music/27.png',
                'category' => 'music',
                'description' => '8 capsules with #music shared with 8 different users from different countries',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Soundtrack of a Year',
                'icon' => 'badges/music/28.png',
                'category' => 'music',
                'description' => 'Post a capsule with #music for 365 days in a row',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'The Eternal Beat',
                'icon' => 'badges/music/29.png',
                'category' => 'music',
                'description' => 'Post a capsule with #music every day for 2 years in a row',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
