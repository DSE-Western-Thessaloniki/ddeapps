<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\MailMerge;
use Illuminate\Support\Facades\Auth;

class MailMergeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mailmerges = MailMerge::all();
        return view('apps.mailmerge.index')->with('mailmerges', $mailmerges);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $doc_logos = DB::table('doc_logos')->get();
        $exact_copies = DB::table('exact_copies')->get();
        $signatures = DB::table('signatures')->get();
        $doc_addresses = DB::table('doc_addresses')->get();

        return view('apps.mailmerge.create')
                ->with('doc_logos', $doc_logos)
                ->with('exact_copies', $exact_copies)
                ->with('signatures', $signatures)
                ->with('doc_addresses', $doc_addresses);
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
            'protocol' => 'required',
            'logoselect' => 'required',
            'addressselect' => 'required',
            'exactcopyselect' => 'required',
            'signatureselect' => 'required',
        ]);

        $signature = new MailMerge([
            'user_id' => Auth::user()->id,
            'logo_id' => $request->get('logoselect'),
            'address_id' => $request->get('addressselect'),
            'protocol_num' => $request->get('protocol'),
            'date' => $request->get('date'),
            'subject' => $request->get('subject'),
            'text' => $request->get('text'),
            'exact_copy_id' => $request->get('exactcopyselect'),
            'signature_id' => $request->get('signatureselect'),
            'xlsxdata' => $request->get('xlsxdata'),
            'xlsxdata_header' => $request->get('xlsxdata_header'),
            'mergefields' => $request->get('mergefields'),
        ]);
        $signature->save();
        return redirect(route('apps.mailmerge.index'))->with('status', 'Mail merge saved!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $mailmerge = MailMerge::find($id);
        $doc_logo = DB::table('doc_logos')->find($mailmerge->logo_id);
        $exact_copy = DB::table('exact_copies')->find($mailmerge->exact_copy_id);
        $signature = DB::table('signatures')->find($mailmerge->signature_id);
        $doc_address = DB::table('doc_addresses')->find($mailmerge->address_id);

        return view('apps.mailmerge.show', compact('mailmerge'))
                ->with('doc_logo', $doc_logo)
                ->with('exact_copy', $exact_copy)
                ->with('signature', $signature)
                ->with('doc_address', $doc_address);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $mailmerge = MailMerge::find($id);
        $doc_logos = DB::table('doc_logos')->get();
        $exact_copies = DB::table('exact_copies')->get();
        $signatures = DB::table('signatures')->get();
        $doc_addresses = DB::table('doc_addresses')->get();

        return view('apps.mailmerge.edit', compact('mailmerge'))
                ->with('doc_logos', $doc_logos)
                ->with('exact_copies', $exact_copies)
                ->with('signatures', $signatures)
                ->with('doc_addresses', $doc_addresses);
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
            'protocol'=>'required',
            'logoselect' => 'required',
            'addressselect' => 'required',
            'exactcopyselect' => 'required',
            'signatureselect' => 'required',
        ]);

        $mailmerge = MailMerge::find($id);
        $mailmerge->logo_id = $request->get('logoselect');
        $mailmerge->address_id = $request->get('addressselect');
        $mailmerge->protocol_num = $request->get('protocol');
        $mailmerge->date = $request->get('date');
        $mailmerge->subject = $request->get('subject');
        $mailmerge->text = $request->get('text');
        $mailmerge->exact_copy_id = $request->get('exactcopyselect');
        $mailmerge->signature_id = $request->get('signatureselect');
        $mailmerge->save();

        return redirect(route('apps.mailmerge.index'))->with('status', 'Mail merge updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $mailmerge = MailMerge::find($id);
        $mailmerge->delete();

        return redirect(route('apps.mailmerge.index'))->with('status', 'Mail merge deleted!');
    }
}
