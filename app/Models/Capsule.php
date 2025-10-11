<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Capsule extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'owner_id', 'published'];

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
            ->withTimestamps();
    }

    // Images
    public function images()
    {
        return $this->hasMany(CapsuleImage::class);
    }
}
