<?php

namespace App\Http\Controllers;

use App\Models\Capsule;
use App\Models\CapsuleImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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




    public function destroy($id)
    {
        $capsule = Capsule::findOrFail($id);

        if ($capsule->owner_id !== Auth::id()) {
            abort(403, 'You do not own this capsule.');
        }

        // Delete related images
        foreach ($capsule->images as $image) {
            Storage::delete($image->image_path);
            $image->delete();
        }

        $capsule->delete();

        return back()->with('success', 'Capsule deleted successfully.');
    }

    // Leave invited capsule
    public function leave($id)
    {
        $capsule = Capsule::findOrFail($id);
        $user = Auth::user();

        if (! $capsule->users->contains($user->id)) {
            abort(403, 'You are not part of this capsule.');
        }

        $capsule->users()->detach($user->id);

        return back()->with('success', 'You have left the capsule.');
    }

    // Add new image
    public function addImage(Request $request, $capsuleId)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $capsule = Capsule::findOrFail($capsuleId);
        $user = Auth::user();

        // Only owner or invited user can add
        if ($capsule->owner_id !== $user->id && !$capsule->users->contains($user->id)) {
            abort(403, 'You do not have permission to add images.');
        }

        $path = $request->file('image')->store('capsule_images', 'public');

        CapsuleImage::create([
            'capsule_id' => $capsule->id,
            'user_id' => $user->id,
            'image_path' => $path,
        ]);

        return back()->with('success', 'Image added successfully.');
    }

    // Delete image
    public function deleteImage($id)
    {
        $image = CapsuleImage::findOrFail($id);
        $user = Auth::user();

        // Only owner can delete any image, others only their own
        if ($image->user_id !== $user->id && $image->capsule->owner_id !== $user->id) {
            abort(403, 'You do not have permission to delete this image.');
        }

        Storage::delete($image->image_path);
        $image->delete();

        return back()->with('success', 'Image deleted successfully.');
    }


    public function updateHashtags(Request $request, $id)
{
    $request->validate([
        'hashtags' => 'array',
        'hashtags.*' => 'string',
    ]);

    $capsule = Capsule::findOrFail($id);

    if ($capsule->owner_id !== Auth::id()) {
        abort(403, 'Unauthorized');
    }

    $capsule->description = implode(' ', $request->hashtags);
    $capsule->save();

    return back();
}

}

