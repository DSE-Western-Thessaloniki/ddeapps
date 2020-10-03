<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DocLogo;

class DocLogoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doclogos = DocLogo::all();

        return view('apps.mailmerge.doclogo.index')->with('doclogos', $doclogos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('apps.mailmerge.doclogo.create');
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
            'doclogotitle'=>'required',
        ]);

        $doclogo = new DocLogo([
            'title' => $request->get('doclogotitle'),
            'image' => $request->get('image'),
            'text' => $request->get('doclogotext'),
            'active' => $request->get('active') == 1 ? 1 : 0,
        ]);
        $doclogo->save();
        return redirect(route('apps.mailmerge.doclogo.index'))->with('status', 'Logo saved!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $doclogo = DocLogo::find($id);
        return view('apps.mailmerge.doclogo.edit', compact('doclogo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'doclogotitle'=>'required',
        ]);

        $doclogo = DocLogo::find($id);
        $doclogo->title = $request->get('doclogotitle');
        $doclogo->image = $request->get('image');
        $doclogo->text = $request->get('doclogotext');
        $doclogo->active = $request->get('active') == 1 ? 1 : 0;
        $doclogo->save();

        return redirect(route('apps.mailmerge.doclogo.index'))->with('status', 'Logo updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $doclogo = DocLogo::find($id);
        $doclogo->delete();

        return redirect(route('apps.mailmerge.doclogo.index'))->with('status', 'Logo deleted!');
    }
}
