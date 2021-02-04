<?php

namespace App\Http\Controllers;

use App\Recipient;
use Illuminate\Http\Request;

class RecipientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recipients = Recipient::all();
        return view('apps.mailmerge.recipient.index')->with('recipients', $recipients);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('apps.mailmerge.recipient.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name'=>'required',
                'code' => 'required',
            ]
        );

        $recipient = new Recipient(
            [
                'name' => $request->get('name'),
                'code' => $request->get('code'),
            ]
        );
        $recipient->save();
        return redirect(route('apps.mailmerge.recipient.index'))->with('status', 'Recipient saved!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $recipient = Recipient::find($id);
        return view('apps.mailmerge.recipient.show', compact('recipient'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $recipient = Recipient::find($id);
        return view('apps.mailmerge.recipient.edit', compact('recipient'));
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
        $request->validate(
            [
                'name'=>'required',
                'code' => 'required'
            ]
        );

        $recipient = Recipient::find($id);
        $recipient->name = $request->get('name');
        $recipient->code = $request->get('code');
        $recipient->save();

        return redirect(route('apps.mailmerge.recipient.index'))->with('status', 'Recipient updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $recipient = Recipient::find($id);
        $recipient->delete();

        return redirect(route('apps.mailmerge.recipient.index'))->with('status', 'Recipient deleted!');
    }
}
