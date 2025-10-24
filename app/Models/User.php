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

    // Start from today, check each day backward
    $currentDate = now()->startOfDay();

    while (true) {
        // Check if there is at least one published capsule on this day
        $hasPublished = $capsules->contains(function ($capsule) use ($currentDate) {
            return $capsule->is_ready() && $capsule->created_at->isSameDay($currentDate);
        });

        if ($hasPublished) {
            $streak++;
            $currentDate->subDay(); // go to previous day
        } else {
            break; // streak broken
        }
    }

    return $streak;
}

    
}


