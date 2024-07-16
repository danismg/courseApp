<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        return view('front.index');
    }

    public function details(Course $course)
    // Course $course -> model binding
    {
        return view('front.details');
    }
}
