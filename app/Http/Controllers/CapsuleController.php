<?php

namespace App\Http\Controllers;

use App\Models\Capsule;
use App\Models\CapsuleImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CapsuleController extends Controller
{
    public function store(Request $request)
    {
        
        $request->validate([
            'description' => 'required|string',
            'published' => 'boolean',
            'invited' => 'array',
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

        $user = Auth::user();

        if ($capsule->is_ready()) {
        $newStreak = $user->streak();

        $user->update(['streak_days' => $newStreak]);
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
            'image' => 'required',
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


public function toggleReady(Capsule $capsule)
{
    $user = Auth::user();

    // If the user is the owner, toggle capsules.ready
    if ($capsule->owner_id === $user->id) {
        $capsule->ready = !$capsule->ready;
        $capsule->save();
    } else {
        // Otherwise toggle pivot ready
        $currentReady = $capsule->users()
            ->where('user_id', $user->id)
            ->first()
            ->pivot
            ->ready ?? false;

        $capsule->users()->updateExistingPivot($user->id, [
            'ready' => !$currentReady,
        ]);
    }

    return back();
}


public function inviteMore(Request $request, Capsule $capsule)
{
    $request->validate([
        'invited' => 'required|array',
        'invited.*' => 'exists:users,id',
    ]);

    // Attach new users only if not already attached
    $existing = $capsule->users()->pluck('users.id')->toArray();
    $newInvites = array_diff($request->invited, $existing);

    if (!empty($newInvites)) {
        foreach ($newInvites as $userId) {
            $capsule->users()->attach($userId, ['ready' => 0]);
        }
    }

    return back()->with('success', 'Friends invited successfully!');
}


public function removeInvitation(Request $request, $capsuleId, $userId)
{
    $capsule = Capsule::findOrFail($capsuleId);

    // Ensure only the owner can remove invitations
    if ($capsule->owner_id !== Auth::id()) {
        abort(403, 'Unauthorized');
    }

    $capsule->users()->detach($userId);

    return back()->with('success', 'Invitation removed successfully!');

}

public function updateVisibility(Request $request, Capsule $capsule)
{
    // Check if the authenticated user is the owner
    if ($capsule->owner_id !== auth()->id()) {
        return back()->with('error', 'Not allowed');
    }

    // Validate the input
    $data = $request->validate([
        'visible_to' => 'required|in:me,friends,everyone',
    ]);

    // Update capsule
    $capsule->visible_to = $data['visible_to'];
    $capsule->save();

    return back()->with('success', 'Visibility changed successfully!');
}


}

