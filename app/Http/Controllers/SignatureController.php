<?php

namespace App\Http\Controllers;

use App\Signature;
use Illuminate\Http\Request;

class SignatureController extends Controller
{
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
        ]);
        $signature->save();
        return redirect(route('apps.mailmerge.signature.index'))->with('status', 'Signature saved!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $signature = Signature::find($id);
        return view('apps.mailmerge.signature.show', compact('signature'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $signature = Signature::find($id);
        return view('apps.mailmerge.signature.edit', compact('signature'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'title'=>'required',
        ]);

        $signature = Signature::find($id);
        $signature->title = $request->get('title');
        $signature->text = $request->get('text');
        $signature->active = $request->get('active') == 1 ? 1 : 0;
        $signature->save();

        return redirect(route('apps.mailmerge.signature.index'))->with('status', 'Signature updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $signature = Signature::find($id);
        $signature->delete();

        return redirect(route('apps.mailmerge.signature.index'))->with('status', 'Signature deleted!');
    }
}
