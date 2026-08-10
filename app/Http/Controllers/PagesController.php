<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;

class PagesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        return redirect('login');
    }
}
