<?php

namespace App\Http\Controllers\MailMerge;

use App\Models\MailMerge\Signature;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SignatureController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Signature::class, 'signature');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $signatures = Signature::all();
        return view('apps.mailmerge.signature.index')->with('signatures', $signatures);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('apps.mailmerge.signature.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
        ]);

        $signature = new Signature([
            'title' => $request->get('title'),
            'text' => $request->get('text'),
            'active' => $request->get('active') == 1 ? 1 : 0,
            'updated_by' => Auth::user()->id,
        ]);
        $signature->save();
        return redirect(route('apps.mailmerge.signature.index'))->with('status', 'Signature saved!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MailMerge\Signature $signature
     * @return \Illuminate\Http\Response
     */
    public function show(Signature $signature)
    {
        return view('apps.mailmerge.signature.show', compact('signature'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MailMerge\Signature $signature
     * @return \Illuminate\Http\Response
     */
    public function edit(Signature $signature)
    {
        return view('apps.mailmerge.signature.edit', compact('signature'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MailMerge\Signature $signature
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Signature $signature)
    {
        $request->validate([
            'title'=>'required',
        ]);

        $signature->title = $request->get('title');
        $signature->text = $request->get('text');
        $signature->active = $request->get('active') == 1 ? 1 : 0;
        $signature->updated_by = Auth::user()->id;
        $signature->save();

        return redirect(route('apps.mailmerge.signature.index'))->with('status', 'Signature updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MailMerge\Signature $signature
     * @return \Illuminate\Http\Response
     */
    public function destroy(Signature $signature)
    {
        $signature->delete();

        return redirect(route('apps.mailmerge.signature.index'))->with('status', 'Signature deleted!');
    }
}
