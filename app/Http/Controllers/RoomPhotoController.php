<?php

namespace App\Http\Controllers;

use App\Models\RoomPhoto;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomPhotoController extends Controller
{
    public function uploadPhotos(Request $request, $roomId)
    {
        $request->validate([
            'photos.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $room = Room::findOrFail($roomId);
        $uploadedPhotos = [];

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('room_photos', 'public');

                $uploadedPhotos[] = RoomPhoto::create([
                    'room_id' => $room->id,
                    'photo' => $path,
                ]);
            }
        }

        return redirect()->route('rooms.index', $room->room_type_id)
            ->with('success', count($uploadedPhotos) . ' foto berhasil diupload.');
    }

    public function deletePhoto($photoId)
    {
        $photo = RoomPhoto::findOrFail($photoId);
        $roomTypeId = $photo->room->room_type_id;

        if (Storage::disk('public')->exists($photo->photo)) {
            Storage::disk('public')->delete($photo->photo);
        }

        $photo->delete();

        return redirect()->route('rooms.index', $roomTypeId)
            ->with('success', 'Foto berhasil dihapus.');
    }

    public function getPhotos($roomId)
    {
        $photos = RoomPhoto::where('room_id', $roomId)->get();
        return response()->json($photos);
    }
}