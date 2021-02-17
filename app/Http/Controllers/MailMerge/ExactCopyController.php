<?php

namespace App\Http\Controllers\MailMerge;

use App\Models\MailMerge\ExactCopy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ExactCopyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exactcopies = ExactCopy::all();
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
            'title'=>'required',
        ]);

        $exactcopy = new ExactCopy([
            'title' => $request->get('title'),
            'text' => $request->get('text'),
            'active' => $request->get('active') == 1 ? 1 : 0,
            'updated_by' => Auth::user()->id,
        ]);
        $exactcopy->save();
        return redirect(route('apps.mailmerge.exactcopy.index'))->with('status', 'Exact copy saved!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $exactcopy = ExactCopy::find($id);
        return view('apps.mailmerge.exactcopy.show', compact('exactcopy'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $exactcopy = ExactCopy::find($id);
        return view('apps.mailmerge.exactcopy.edit', compact('exactcopy'));
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

        $exactcopy = ExactCopy::find($id);
        $exactcopy->title = $request->get('title');
        $exactcopy->text = $request->get('text');
        $exactcopy->active = $request->get('active') == 1 ? 1 : 0;
        $exactcopy->updated_by = Auth::user()->id;
        $exactcopy->save();

        return redirect(route('apps.mailmerge.exactcopy.index'))->with('status', 'Exact copy updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $exactcopy = ExactCopy::find($id);
        $exactcopy->delete();

        return redirect(route('apps.mailmerge.exactcopy.index'))->with('status', 'Exact copy deleted!');
    }
}
