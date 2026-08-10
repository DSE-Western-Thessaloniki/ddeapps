<?php

namespace App\Http\Controllers\MailMerge;

use App\Http\Controllers\Controller;
use App\Models\MailMerge\DocLogo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DocLogoController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(DocLogo::class, 'doclogo');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $doclogos = DocLogo::with('creator')->get();

        return view('apps.mailmerge.doclogo.index')->with('doclogos', $doclogos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('apps.mailmerge.doclogo.create');
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
            'text' => 'string|max:65535',
            'image' => 'string|max:255',
            'active' => 'boolean',
        ]);

        $doclogo = new DocLogo([
            'title' => $request->get('title'),
            'image' => $request->get('image'),
            'text' => $request->get('text'),
            'active' => $request->get('active') == 1 ? 1 : 0,
            'updated_by' => Auth::user()->id,
            'created_by' => Auth::user()->id,
        ]);
        $doclogo->save();

        return redirect(route('apps.mailmerge.doclogo.index'))->with('status', 'Logo saved!');
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function show(DocLogo $doclogo)
    {
        return view('apps.mailmerge.doclogo.show', compact('doclogo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit(DocLogo $doclogo)
    {
        return view('apps.mailmerge.doclogo.edit', compact('doclogo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(Request $request, DocLogo $doclogo)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $doclogo->title = $request->get('title');
        $doclogo->image = $request->get('image');
        $doclogo->text = $request->get('text');
        $doclogo->active = $request->get('active') == 1 ? 1 : 0;
        $doclogo->updated_by = Auth::user()->id;
        $doclogo->save();

        return redirect(route('apps.mailmerge.doclogo.index'))->with('status', 'Logo updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy(DocLogo $doclogo)
    {
        $doclogo->delete();

        return redirect(route('apps.mailmerge.doclogo.index'))->with('status', 'Logo deleted!');
    }
}
