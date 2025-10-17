<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Capsule extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'owner_id', 'ready', 'visible_to'];

    protected $casts = [
        'published' => 'boolean',
    ];

    // Owner (creator)
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Invited users
    public function users()
    {
        return $this->belongsToMany(User::class, 'capsule_user')
            ->withPivot('ready')
            ->withTimestamps();
    }

    // Images
    public function images()
    {
        return $this->hasMany(CapsuleImage::class);
    }

     public function is_ready(): bool
    {
        if ($this->ready != 1) {
            return false;
        }

        // Check if all invited users are ready
        $notReadyFriends = $this->users()->wherePivot('ready', '!=', 1)->exists();

        // Capsule is ready only if all are ready
        return !$notReadyFriends;
    }
}
