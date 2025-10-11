<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CapsuleImage extends Model
{
    use HasFactory;

    protected $fillable = ['capsule_id', 'user_id', 'image_path'];

    public function capsule()
    {
        return $this->belongsTo(Capsule::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
