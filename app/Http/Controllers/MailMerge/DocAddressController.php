<?php

namespace App\Http\Controllers\MailMerge;

use Illuminate\Http\Request;
use App\Models\MailMerge\DocAddress;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DocAddressController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(DocAddress::class, 'docaddress');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $docaddresses = DocAddress::all();
        return view('apps.mailmerge.docaddress.index')->with('docaddresses', $docaddresses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('apps.mailmerge.docaddress.create');
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

        $docaddress = new DocAddress([
            'title' => $request->get('title'),
            'address' => $request->get('address'),
            'name' => $request->get('name'),
            'telephone' => $request->get('telephone'),
            'email' => $request->get('email'),
            'updated_by' => Auth::user()->id,
        ]);

        $docaddress->save();
        return redirect(route('apps.mailmerge.docaddress.index'))->with('status', 'Address saved!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MailMerge\DocAddress $docaddress
     * @return \Illuminate\Http\Response
     */
    public function show(DocAddress $docaddress)
    {
        return view('apps.mailmerge.docaddress.show', compact('docaddress'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MailMerge\DocAddress $docaddress
     * @return \Illuminate\Http\Response
     */
    public function edit(DocAddress $docaddress)
    {
        return view('apps.mailmerge.docaddress.edit', compact('docaddress'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MailMerge\DocAddress $docaddress
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DocAddress $docaddress)
    {
        $request->validate([
            'title'=>'required',
        ]);

        $docaddress->title = $request->get('title');
        $docaddress->address = $request->get('address');
        $docaddress->name = $request->get('name');
        $docaddress->telephone = $request->get('telephone');
        $docaddress->email = $request->get('email');
        $docaddress->updated_by = Auth::user()->id;
        $docaddress->save();

        return redirect(route('apps.mailmerge.docaddress.index'))->with('status', 'Address updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MailMerge\DocAddress $docaddress
     * @return \Illuminate\Http\Response
     */
    public function destroy(DocAddress $docaddress)
    {
        $docaddress->delete();

        return redirect(route('apps.mailmerge.docaddress.index'))->with('status', 'Address deleted!');
    }
}
