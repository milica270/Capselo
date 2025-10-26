<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{

    use Notifiable;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'lastName',
        'email',
        'password',
        'birthdate',
        'country',
        'city',
        'role',
        'schoolType',
        'jobType',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


        
    public function friends()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
                    ->wherePivot('accepted', true)
                    ->withTimestamps();
    }

    public function friendRequests()
    {
        return $this->hasMany(Friendship::class, 'friend_id')->where('accepted', false);
    }

    public function sentRequests()
    {
        return $this->hasMany(Friendship::class, 'user_id')->where('accepted', false);
    }

    public function ownedCapsules()
    {
        return $this->hasMany(Capsule::class, 'owner_id');
    }

    public function invitedCapsules()
    {
        return $this->belongsToMany(Capsule::class, 'capsule_user')->withPivot('ready')->withTimestamps();
    }




    // In User.php (User model)
public function streak()
{
    // Get all capsules of the user, ordered by date descending (latest first)
    $capsules = $this->ownedCapsules()
        ->whereDate('created_at', '<=', now()) // only up to today
        ->orderBy('created_at', 'desc')
        ->get();

    $streak = 0;
    $currentDate = now()->startOfDay();

    // Check if user has published today
    $hasPublishedToday = $capsules->contains(function ($capsule) use ($currentDate) {
        return $capsule->is_ready() && $capsule->created_at->isSameDay($currentDate);
    });

    // If user hasn’t published today, start checking from yesterday instead
    $dateToCheck = $hasPublishedToday ? $currentDate : $currentDate->copy()->subDay();

    while (true) {
        $hasPublished = $capsules->contains(function ($capsule) use ($dateToCheck) {
            return $capsule->is_ready() && $capsule->created_at->isSameDay($dateToCheck);
        });

        if ($hasPublished) {
            $streak++;
            $dateToCheck->subDay();
        } else {
            break;
        }
    }

    $urgent = $streak === 0 ? false : !$hasPublishedToday;
    // Return both streak and urgency indicator
    return [
        'count' => $streak,
        'urgent' => $urgent, // true if user hasn’t published today
    ];
}

}


