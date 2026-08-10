<?php

namespace App\Http\Controllers\MailMerge;

use App\Http\Controllers\Controller;
use App\Models\MailMerge\Editor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
     * @return Response
     */
    public function index()
    {
        $editors = Editor::with('creator')->get();

        return view('apps.mailmerge.editor.index')->with('editors', $editors);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('apps.mailmerge.editor.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'string|max:255|required',
            'address' => 'string|max:255',
            'name' => 'string|max:255',
            'telephone' => 'string|max:255',
            'email' => 'string|max:255',
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
     * @return Response
     */
    public function show(Editor $editor)
    {
        return view('apps.mailmerge.editor.show', compact('editor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit(Editor $editor)
    {
        return view('apps.mailmerge.editor.edit', compact('editor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(Request $request, Editor $editor)
    {
        $request->validate([
            'title' => 'string|max:255|required',
            'address' => 'string|max:255',
            'name' => 'string|max:255',
            'telephone' => 'string|max:255',
            'email' => 'string|max:255',
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
     * @return Response
     */
    public function destroy(Editor $editor)
    {
        $editor->delete();

        return redirect(route('apps.mailmerge.editor.index'))->with('status', __('Editor deleted!'));
    }
}
