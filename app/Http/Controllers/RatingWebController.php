<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingWebController extends Controller
{
    public function index()
    {
        $ratings = Rating::with(['user', 'hotel', 'roomType'])->latest()->get();

        return view('admin.ratings.index', compact('ratings'));
    }
}
