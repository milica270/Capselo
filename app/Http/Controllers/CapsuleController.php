<?php

namespace App\Http\Controllers;

use App\Models\Capsule;
use App\Models\CapsuleImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CapsuleController extends Controller
{
    public function store(Request $request)
    {
        
        $request->validate([
            'description' => 'required|string',
            'published' => 'boolean',
            'invited' => 'array',
            'images.*' => 'image|max:2048',
            'visible_to' => 'required',
        ]);

        $capsule = Capsule::create([
            'owner_id' => auth()->id(),
            'description' => $request->description,
            'ready' => $request->boolean('published'),
            'visible_to' => $request->visible_to,
        ]);

        // Invite friends
        if ($request->has('invited')) {
            $capsule->users()->attach($request->invited);
        }

        // Upload images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('capsules', 'public');
                CapsuleImage::create([
                    'capsule_id' => $capsule->id,
                    'user_id' => auth()->id(),
                    'image_path' => $path,
                ]);
            }
        }

        return back()->with('success', 'Capsule created successfully!');
    }
}

