<?php

namespace App\Http\Controllers\MailMerge;

use App\Models\MailMerge\ExactCopy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ExactCopyController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(ExactCopy::class, 'exactcopy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exactcopies = ExactCopy::with('creator')->get();
        return view('apps.mailmerge.exactcopy.index')->with('exactcopies', $exactcopies);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('apps.mailmerge.exactcopy.create');
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
            'title'=>'required|string|max:255',
            'text'=>'required|string|max:65535',
            'active'=>'boolean',
        ]);

        $exactcopy = new ExactCopy([
            'title' => $request->get('title'),
            'text' => $request->get('text'),
            'active' => $request->get('active') == 1 ? 1 : 0,
            'updated_by' => Auth::user()->id,
            'created_by' => Auth::user()->id,
        ]);
        $exactcopy->save();
        return redirect(route('apps.mailmerge.exactcopy.index'))->with('status', __('Exact copy saved!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MailMerge\ExactCopy $exactcopy
     * @return \Illuminate\Http\Response
     */
    public function show(ExactCopy $exactcopy)
    {
        return view('apps.mailmerge.exactcopy.show', compact('exactcopy'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MailMerge\ExactCopy $exactcopy
     * @return \Illuminate\Http\Response
     */
    public function edit(ExactCopy $exactcopy)
    {
        return view('apps.mailmerge.exactcopy.edit', compact('exactcopy'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MailMerge\ExactCopy $exactcopy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExactCopy $exactcopy)
    {
        $request->validate([
            'title'=>'required',
        ]);

        $exactcopy->title = $request->get('title');
        $exactcopy->text = $request->get('text');
        $exactcopy->active = $request->get('active') == 1 ? 1 : 0;
        $exactcopy->updated_by = Auth::user()->id;
        $exactcopy->save();

        return redirect(route('apps.mailmerge.exactcopy.index'))->with('status', __('Exact copy updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MailMerge\ExactCopy $exactcopy
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExactCopy $exactcopy)
    {
        $exactcopy->delete();

        return redirect(route('apps.mailmerge.exactcopy.index'))->with('status', __('Exact copy deleted!'));
    }
}
