<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MailMergeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('apps.mailmerge.index');
    }

    public function create()
    {
        return view('apps.mailmerge.create');
    }

    public function show()
    {
        return view('apps.mailmerge.show');
    }
}
