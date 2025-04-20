<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PageController extends Controller
{
    public function index(){


        return View::make('page.index');
    }

    public function about()
    {
        //echo "amiteshsingh";
        return view('page.about');
    }

    public function doctor()
    {
        return view('page.doctors');
    }

    public function hospital()
    {
        return view('page.hospitals');
    }

    public function blog()
    {
        return view('page.blog');
    }

    public function detail()
    {
        return view('page.detail');
    }
    
    public function team()
    {
        return view('page.team');
    }

    public function testimonial()
    {
        return view('page.testimonial');
    }

    public function appointment()
    {
        return view('page.appointment');
    }

    public function search()
    {
        return view('page.search');
    }

    public function contact()
    {
        return view('page.contact');
    }
}
