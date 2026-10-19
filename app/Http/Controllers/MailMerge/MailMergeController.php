<?php

namespace App\Http\Controllers\MailMerge;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateMailMergeZip;
use App\Models\MailMerge\MailMerge;
use App\Models\MailMerge\MailMergeExport;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Facades\Pdf;
use ZipArchive;

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
     * @return Response
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter');
        if ($filter) {
            $mailmerges = MailMerge::with('creator')
                ->where('id', $filter)
                ->orWhere('protocol_num', $filter)
                ->orWhere('subject', $filter)
                ->orderBy('id', 'desc')
                ->paginate(5);
        } else {
            $mailmerges = MailMerge::with('creator')->orderBy('id', 'desc')->paginate(5);
        }

        return view('apps.mailmerge.index')
            ->with('mailmerges', $mailmerges)
            ->with('filter', $filter);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
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
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'protocol_num' => 'string|max:255|required',
            'logo_id' => 'numeric|required',
            'editor_id' => 'numeric|required',
            'exact_copy_id' => 'numeric|required',
            'signature_id' => 'numeric|required',
            'ada' => 'nullable|string|max:255',
            'date' => 'date',
            'subject' => 'string|max:65535',
            'text' => 'string|max:65535',
            'xlsxdata' => 'string',
            'xlsxdata_headers' => 'string',
            'mergefields' => 'string',
            'files_for_teachers' => 'boolean',
        ]);

        $signature = new MailMerge([
            'logo_id' => $request->get('logo_id'),
            'editor_id' => $request->get('editor_id'),
            'ada' => $request->get('ada'),
            'protocol_num' => $request->get('protocol_num'),
            'date' => $request->get('date'),
            'subject' => $request->get('subject'),
            'text' => $request->get('text') ?? '',
            'exact_copy_id' => $request->get('exact_copy_id'),
            'signature_id' => $request->get('signature_id'),
            'xlsxdata' => $request->get('xlsxdata'),
            'xlsxdata_header' => $request->get('xlsxdata_header'),
            'mergefields' => $request->get('mergefields'),
            'files_for_teachers' => ($request->get('files_for_teachers') === 'on' ||
                                     $request->get('files_for_teachers') === '1' ||
                                     $request->get('files_for_teachers') === true) ? true : false,
            'updated_by' => Auth::user()->id,
            'created_by' => Auth::user()->id,
        ]);
        $signature->save();

        return redirect(route('apps.mailmerge.index'))->with('status', __('Mail merge saved!'));
    }

    /**
     * Display the specified resource.
     *
     * @return Response
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
     * @return Response
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
     * @return Response
     */
    public function update(Request $request, MailMerge $mailmerge)
    {
        $request->validate([
            'protocol_num' => 'string|max:255|required',
            'logo_id' => 'numeric|required',
            'editor_id' => 'numeric|required',
            'exact_copy_id' => 'numeric|required',
            'signature_id' => 'numeric|required',
            'ada' => 'nullable|string|max:255',
            'date' => 'date',
            'subject' => 'string|max:65535',
            'text' => 'string|max:65535',
            'xlsxdata' => 'string',
            'xlsxdata_headers' => 'string',
            'mergefields' => 'string',
            'files_for_teachers' => 'boolean',
        ]);

        // dd($request->get('files_for_teachers'));
        $mailmerge->logo_id = $request->get('logo_id');
        $mailmerge->editor_id = $request->get('editor_id');
        $mailmerge->ada = $request->get('ada') ?? '';
        $mailmerge->protocol_num = $request->get('protocol_num');
        $mailmerge->date = $request->get('date');
        $mailmerge->subject = $request->get('subject');
        $mailmerge->text = $request->get('text');
        $mailmerge->exact_copy_id = $request->get('exact_copy_id');
        $mailmerge->signature_id = $request->get('signature_id');
        $mailmerge->xlsxdata = $request->get('xlsxdata');
        $mailmerge->xlsxdata_header = $request->get('xlsxdata_header');
        $mailmerge->mergefields = $request->get('mergefields');
        $mailmerge->files_for_teachers = ($request->get('files_for_teachers') === 'on' ||
                                          $request->get('files_for_teachers') === '1' ||
                                          $request->get('files_for_teachers') === true) ? true : false;
        $mailmerge->updated_by = Auth::user()->id;
        $mailmerge->save();

        return redirect(route('apps.mailmerge.index'))->with('status', __('Mail merge updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
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

        $data = ['id', 'editor', 'exact_copy', 'signature', 'doc_logo', 'draft'];
        $pdf = Pdf::view('apps.mailmerge.print', compact('mailmerge', $data))
            ->format('a4')
            ->margins(0, 0, 0, 0);
        $filename = 'mailmerge-'.$mailmerge->protocol_num.'-'.date('Ymd-His').'.pdf';

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

    public function save(Request $request, int $id)
    {
        $mailmerge = MailMerge::find($id);
        if ($mailmerge === null) {
            abort(404);
        }
        $this->authorize('view', $mailmerge);

        $records = json_decode($mailmerge->xlsxdata, true) ?? [];
        $export = MailMergeExport::create([
            'user_id' => $request->user()->id,
            'mail_merge_id' => $mailmerge->id,
            'status' => MailMergeExport::STATUS_PENDING,
            'total' => count($records),
            'processed' => 0,
        ]);

        GenerateMailMergeZip::dispatch($export);

        return response()->json([
            'export_id' => $export->id,
            'status' => $export->status,
            'total' => $export->total,
            'processed' => $export->processed,
            'status_url' => route('apps.mailmerge.exports.status', ['export' => $export]),
            'download_url' => route('apps.mailmerge.exports.download', ['export' => $export]),
        ], 201);
    }

    public function exportStatus(Request $request, MailMergeExport $export)
    {
        $this->authorize('view', $export);

        return response()->json([
            'export_id' => $export->id,
            'status' => $export->status,
            'total' => $export->total,
            'processed' => $export->processed,
            'error' => $export->error,
            'download_url' => route('apps.mailmerge.exports.download', ['export' => $export]),
        ]);
    }

    public function exportDownload(Request $request, MailMergeExport $export)
    {
        $this->authorize('view', $export);

        if (! $export->isReady()) {
            abort(409);
        }

        if ($export->path === null || ! Storage::exists($export->path)) {
            abort(404);
        }

        $filename = $export->mailMerge->protocol_num.'-'.$export->created_at->format('YmdHis').'.zip';
        $path = Storage::path($export->path);
        $export->delete();

        return response()->download($path, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Make a copy of the resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function copy(MailMerge $mailmerge)
    {
        $this->authorize('create', MailMerge::class);
        $copy = $mailmerge->replicate();
        $copy->save();

        return redirect(route('apps.mailmerge.index'))->with('status', __('Mail merge copied!'));
    }

    public function confirmDelete(MailMerge $mailmerge)
    {
        $this->authorize('delete', $mailmerge);

        return view('apps.mailmerge.confirm_delete', compact('mailmerge'));
    }

    public function uploadForm(MailMerge $mailmerge)
    {
        $this->authorize('create', MailMerge::class);

        return view('apps.mailmerge.upload_form', compact('mailmerge'));
    }

    public function uploadFiles(Request $request, MailMerge $mailmerge)
    {
        $this->authorize('update', $mailmerge);

        /** @var UploadedFile $file */
        foreach ($request->file('signed') as $file) {
            if (
                $file->getMimeType() !== 'application/pdf' ||
                $file->storeAs("signed/$mailmerge->id", $file->getClientOriginalName()) === false
            ) {
                return redirect(route('apps.mailmerge.upload_form', $mailmerge->id))
                    ->with('status', __('Failed to upload signed file').' '.$file->getClientOriginalName());
            }
        }

        return redirect(route('apps.mailmerge.show', $mailmerge->id))->with('status', __('Signed documents uploaded!'));
    }

    public function signedFiles(MailMerge $mailmerge)
    {
        $this->authorize('view', $mailmerge);

        $files = $mailmerge->signedFiles();

        return view('apps.mailmerge.signed_files', compact('mailmerge', 'files'));
    }

    public function signedFile(MailMerge $mailmerge, string $filename)
    {
        $this->authorize('view', $mailmerge);

        if (! Storage::exists("signed/$mailmerge->id/$filename")) {
            abort(404);
        }

        return Storage::download("signed/$mailmerge->id/$filename");
    }

    public function getZipFile(Request $request, MailMerge $mailmerge)
    {
        $this->authorize('view', $mailmerge);

        // Cleanup temporary files
        $oldFiles = Storage::allFiles("tmp/user/{$request->user()->id}");
        foreach ($oldFiles as $file) {
            Storage::delete($file);
        }

        $files = Storage::allFiles("signed/$mailmerge->id");
        if (! $files) {
            abort(404);
        }

        $now = DateTime::createFromFormat('U.u', microtime(true));
        $zip = new ZipArchive;
        $zip_path = "/tmp/user/{$request->user()->id}/";
        Storage::makeDirectory($zip_path);
        $zip_name = $now->format('YmdHisu').'.zip';
        $zip->open(storage_path('app').$zip_path.$zip_name, ZipArchive::CREATE);

        foreach ($files as $file) {
            $filename = basename($file);
            $file_path = storage_path('app')."/signed/{$mailmerge->id}/{$filename}";

            $zip->addFile($file_path, $filename);
            $zip->setCompressionName($filename, ZipArchive::CM_STORE);
        }

        $zip->close();

        return response()->download(storage_path('app').$zip_path.$zip_name);
    }
}
