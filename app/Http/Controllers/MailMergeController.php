<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\MailMerge;

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
        $mailmerges = MailMerge::all();
        return view('apps.mailmerge.index')->with('mailmerges', $mailmerges);
    }

    public function create()
    {
        $logos = DB::table('mmdoclogo')->get();

        return view('apps.mailmerge.create')->with('logos', $logos);
    }

    public function show()
    {
        return view('apps.mailmerge.show');
    }
}
