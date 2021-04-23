<?php

namespace App\Http\Controllers\MailMerge;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MailMerge\MailMerge;
use Illuminate\Support\Facades\Auth;
use PDF;
use ZipArchive;
use App\Http\Controllers\Controller;

class MailMergeController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(MailMerge::class, 'mailmerge');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mailmerges = MailMerge::orderBy('id', 'desc')->paginate(5);
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
        $editors = DB::table('editors')->get();

        return view('apps.mailmerge.create')
                ->with('doc_logos', $doc_logos)
                ->with('exact_copies', $exact_copies)
                ->with('signatures', $signatures)
                ->with('editors', $editors);
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
            'editorselect' => 'required',
            'exactcopyselect' => 'required',
            'signatureselect' => 'required',
        ]);

        $signature = new MailMerge([
            'logo_id' => $request->get('logoselect'),
            'editor_id' => $request->get('editorselect'),
            'protocol_num' => $request->get('protocol'),
            'date' => $request->get('date'),
            'subject' => $request->get('subject'),
            'text' => $request->get('text'),
            'exact_copy_id' => $request->get('exactcopyselect'),
            'signature_id' => $request->get('signatureselect'),
            'xlsxdata' => $request->get('xlsxdata'),
            'xlsxdata_header' => $request->get('xlsxdata_header'),
            'mergefields' => $request->get('mergefields'),
            'updated_by' => Auth::user()->id,
            'created_by' => Auth::user()->id,
        ]);
        $signature->save();
        return redirect(route('apps.mailmerge.index'))->with('status', __('Mail merge saved!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MailMerge\MailMerge $mailmerge
     * @return \Illuminate\Http\Response
     */
    public function show(MailMerge $mailmerge)
    {
        $doc_logo = DB::table('doc_logos')->find($mailmerge->logo_id);
        $exact_copy = DB::table('exact_copies')->find($mailmerge->exact_copy_id);
        $signature = DB::table('signatures')->find($mailmerge->signature_id);
        $editor = DB::table('editors')->find($mailmerge->editor_id);

        return view('apps.mailmerge.show', compact('mailmerge'))
                ->with('doc_logo', $doc_logo)
                ->with('exact_copy', $exact_copy)
                ->with('signature', $signature)
                ->with('editor', $editor);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MailMerge\MailMerge $mailmerge
     * @return \Illuminate\Http\Response
     */
    public function edit(MailMerge $mailmerge)
    {
        $doc_logos = DB::table('doc_logos')->get();
        $exact_copies = DB::table('exact_copies')->get();
        $signatures = DB::table('signatures')->get();
        $editors = DB::table('editors')->get();

        return view('apps.mailmerge.edit', compact('mailmerge'))
                ->with('doc_logos', $doc_logos)
                ->with('exact_copies', $exact_copies)
                ->with('signatures', $signatures)
                ->with('editors', $editors);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MailMerge\MailMerge $mailmerge
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MailMerge $mailmerge)
    {
        $request->validate([
            'protocol'=>'required',
            'logoselect' => 'required',
            'editorselect' => 'required',
            'exactcopyselect' => 'required',
            'signatureselect' => 'required',
        ]);

        $mailmerge->logo_id = $request->get('logoselect');
        $mailmerge->editor_id = $request->get('editorselect');
        $mailmerge->protocol_num = $request->get('protocol');
        $mailmerge->date = $request->get('date');
        $mailmerge->subject = $request->get('subject');
        $mailmerge->text = $request->get('text');
        $mailmerge->exact_copy_id = $request->get('exactcopyselect');
        $mailmerge->signature_id = $request->get('signatureselect');
        $mailmerge->xlsxdata = $request->get('xlsxdata');
        $mailmerge->xlsxdata_header = $request->get('xlsxdata_header');
        $mailmerge->mergefields = $request->get('mergefields');
        $mailmerge->updated_by = Auth::user()->id;
        $mailmerge->save();

        return redirect(route('apps.mailmerge.index'))->with('status', __('Mail merge updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MailMerge\MailMerge $mailmerge
     * @return \Illuminate\Http\Response
     */
    public function destroy(MailMerge $mailmerge)
    {
        $mailmerge->delete();

        return redirect(route('apps.mailmerge.index'))->with('status', __('Mail merge deleted!'));
    }

    public function print(Request $request, int $id)
    {
        $mailmerge = MailMerge::find($id);
        if ($request->user()->cannot('view', $mailmerge)) {
            abort(403);
        }
        $draft = $request->get('draft');
        $doc_logo = DB::table('doc_logos')->find($mailmerge->logo_id);
        $exact_copy = DB::table('exact_copies')->find($mailmerge->exact_copy_id);
        $signature = DB::table('signatures')->find($mailmerge->signature_id);
        $editor = DB::table('editors')->find($mailmerge->editor_id);

        $data = array('id', 'editor', 'exact_copy', 'signature', 'doc_logo', 'draft');
        $pdf = PDF::loadView('apps.mailmerge.print', compact('mailmerge', $data))
            ->setOptions(['print-media-type' => true,
                          'enable-javascript' => true,
                          'margin-left' => 0,
                          'margin-right' => 0,
                          'margin-top' => 0,
                          'margin-bottom' => 0,
                          'page-size' => 'A4',
                          'disable-smart-shrinking' => true]);
        $filename = "mailmerge-".$mailmerge->protocol_num."-".date('Ymd-His').".pdf";
        return $pdf->inline($filename);
    }

    public function show2(Request $request, int $id)
    {
        $mailmerge = MailMerge::find($id);
        if ($request->user()->cannot('view', $mailmerge)) {
            abort(403);
        }
        $draft = $request->get('draft');
        $doc_logo = DB::table('doc_logos')->find($mailmerge->logo_id);
        $exact_copy = DB::table('exact_copies')->find($mailmerge->exact_copy_id);
        $signature = DB::table('signatures')->find($mailmerge->signature_id);
        $editor = DB::table('editors')->find($mailmerge->editor_id);

        return view('apps.mailmerge.print', compact('mailmerge'))
                ->with('doc_logo', $doc_logo)
                ->with('exact_copy', $exact_copy)
                ->with('signature', $signature)
                ->with('editor', $editor)
                ->with('draft', $draft);
    }

    public function save(int $id)
    {
        $mailmerge = MailMerge::find($id);
        $this->authorize('view', $mailmerge);
        $doc_logo = DB::table('doc_logos')->find($mailmerge->logo_id);
        $exact_copy = DB::table('exact_copies')->find($mailmerge->exact_copy_id);
        $signature = DB::table('signatures')->find($mailmerge->signature_id);
        $editor = DB::table('editors')->find($mailmerge->editor_id);
        $recipients = DB::table('recipients')->select("name", "code")->get();

        $xlsxdata = json_decode($mailmerge->xlsxdata, true);
        $zip_name = '/tmp/'.$mailmerge->protocol_num.'-'.date('YmdHis').'.zip';
        $zip = new ZipArchive;
        $zip->open($zip_name, ZipArchive::CREATE);
        foreach ($xlsxdata as $record) {
            $data = array('id', 'editor', 'exact_copy', 'signature', 'doc_logo', 'record');
            $pdf = PDF::loadView('apps.mailmerge.save', compact('mailmerge', $data))
                ->setOptions(
                    ['print-media-type' => true,
                              'enable-javascript' => true,
                              'margin-left' => 0,
                              'margin-right' => 0,
                              'margin-top' => 0,
                              'margin-bottom' => 0,
                              'page-size' => 'A4',
                              'disable-smart-shrinking' => true]
                );
            $field_array = json_decode($mailmerge->mergefields);
            foreach ($field_array as $mergefield) {
                $recipient_name = $record[$mergefield];
                if ($recipient_name != "") {
                    $key = array_search($recipient_name, array_column($recipients->toArray(), "name"));
                    $recipient_code = $recipients->toArray()[$key]->code;
                    $filename = $mailmerge->protocol_num." ".$record['ΑΜ']." ".$recipient_code.".pdf";
                    $file = $pdf->output();
                    $zip->addFromString($filename, $file);
                    $zip->setCompressionName($filename, ZipArchive::CM_STORE);
                }
            }
        }
        $zip->close();

        return response()->download($zip_name);
    }
}
