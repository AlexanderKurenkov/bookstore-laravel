<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
	public function index(): View
	{
		return view('index');
	}

	public function about(): View
	{
		return view('about');
	}

	public function faq(): View
	{
		return view('faq');
	}
}
