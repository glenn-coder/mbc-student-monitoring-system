<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        // Add logic to fetch instructor's classes here later
        return view('instructor.classes.index');
    }
}
