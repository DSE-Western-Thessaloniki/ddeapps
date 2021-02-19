<?php

namespace App\Http\Controllers\MailMerge;

use Illuminate\Http\Request;
use App\Models\MailMerge\Editor;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EditorController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Editor::class, 'editor');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $editors = Editor::all();
        return view('apps.mailmerge.editor.index')->with('editors', $editors);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('apps.mailmerge.editor.create');
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

        $editor = new Editor([
            'title' => $request->get('title'),
            'address' => $request->get('address'),
            'name' => $request->get('name'),
            'telephone' => $request->get('telephone'),
            'email' => $request->get('email'),
            'updated_by' => Auth::user()->id,
            'created_by' => Auth::user()->id,
        ]);

        $editor->save();
        return redirect(route('apps.mailmerge.editor.index'))->with('status', __('Editor saved!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MailMerge\Editor $editor
     * @return \Illuminate\Http\Response
     */
    public function show(Editor $editor)
    {
        return view('apps.mailmerge.editor.show', compact('editor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MailMerge\Editor $editor
     * @return \Illuminate\Http\Response
     */
    public function edit(Editor $editor)
    {
        return view('apps.mailmerge.editor.edit', compact('editor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MailMerge\Editor $editor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Editor $editor)
    {
        $request->validate([
            'title'=>'required',
        ]);

        $editor->title = $request->get('title');
        $editor->address = $request->get('address');
        $editor->name = $request->get('name');
        $editor->telephone = $request->get('telephone');
        $editor->email = $request->get('email');
        $editor->updated_by = Auth::user()->id;
        $editor->save();

        return redirect(route('apps.mailmerge.editor.index'))->with('status', __('Editor updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MailMerge\Editor $editor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Editor $editor)
    {
        $editor->delete();

        return redirect(route('apps.mailmerge.editor.index'))->with('status', __('Editor deleted!'));
    }
}
