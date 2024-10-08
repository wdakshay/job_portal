<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Jab;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)->orderBy('name', 'ASC')->with('jobs')->get();
        $featuredJobs = Jab::where('isFeatured', 1)
            ->orderBy('created_at', 'DESC')
            ->with('jobType')
            ->take(8)->get();
        $latestJobs = Jab::where('status', 1)
            ->orderBy('created_at', 'DESC')
            ->with('jobType')
            ->take(6)->get();
        return view('front.home', compact('categories', 'featuredJobs', 'latestJobs'));
    }
}
