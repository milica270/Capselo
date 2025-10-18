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


    public function calculateStreak()
    {
        $today = now()->startOfDay();
        $streak = 0;

        // Loop backward day by day
        for ($i = 0; $i < 100; $i++) { // reasonable limit, e.g. 100 days max
            $date = $today->copy()->subDays($i);

            // Get capsules published that day (ready + user is owner or invited)
            $hasCapsule = Capsule::whereDate('created_at', $date)
                ->where(function ($query) {
                    $query->where('owner_id', auth()->id())
                        ->orWhereHas('users', function ($sub) {
                            $sub->where('users.id', auth()->id());
                        });
                })
                ->get()
                ->filter(fn ($capsule) => $capsule->is_ready())
                ->isNotEmpty();

            if ($hasCapsule) {
                $streak++;
            } else {
                break; // stop streak when a day without capsule is found
            }
        }

        // Save to DB
        $this->update(['streak_days' => $streak]);

        return $streak;
    }


    
}


