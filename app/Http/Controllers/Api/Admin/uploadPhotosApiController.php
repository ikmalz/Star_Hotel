<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomPhoto;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadPhotosApiController extends Controller
{
    public function uploadPhotos(Request $request, $roomId)
    {
        $request->validate([
            'photos.*' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $room = Room::findOrFail($roomId);

        $photos = [];

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('room_photos', 'public');

                $photos[] = RoomPhoto::create([
                    'room_id' => $room->id,
                    'photo' => $path,
                ]);
            }
        }

        return response()->json([
            'message' => 'Photos uploaded successfully.',
            'photos' => $photos
        ], 201);
    }
}