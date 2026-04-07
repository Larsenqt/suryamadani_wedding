<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }
    
    public function getTestimonials(Request $request)
    {
        $testimonials = Testimonial::latest()->paginate(6);
        
        return response()->json([
            'data' => $testimonials->items(),
            'current_page' => $testimonials->currentPage(),
            'last_page' => $testimonials->lastPage(),
            'prev_page_url' => $testimonials->previousPageUrl(),
            'next_page_url' => $testimonials->nextPageUrl(),
        ]);
    }
}