<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use App\Models\Comment;
class CommentWebController extends Controller
{



    
public function index()
{
    $comments = Comment::with('user')->latest()->get();
    return view('admin.comments.index', compact('comments'));
}

}
