<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'hotel_id' => 'required|integer|exists:hotels,id',
        'content' => 'required|string|max:500',
    ]);

    $comment = Comment::create([
        'user_id' => auth()->id(),
        'hotel_id' => $request->hotel_id,
        'content' => $request->input('content'),
    ]);

    return response()->json([
        'message' => 'Komentar berhasil ditambahkan',
        'data' => $comment->load('user', 'hotel')
    ]);
}

    public function list($type, $id)
    {
        $modelClass = $type === 'hotel' ? Hotel::class : RoomType::class;
        $model = $modelClass::findOrFail($id);

        $comments = $model->comments()->with('user')->latest()->get();

        return response()->json($comments);
    }
}
