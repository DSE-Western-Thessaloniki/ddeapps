<?php

namespace App\Jobs;

use App\Models\MailMerge\MailMerge;
use App\Models\MailMerge\MailMergeExport;
use App\Services\StringConverter;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Facades\Pdf;
use Throwable;
use ZipArchive;

class GenerateMailMergeZip implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 1;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 3500;

    /**
     * Create a new job instance.
     */
    public function __construct(public MailMergeExport $export) {}

    /**
     * Load the data that does not change between records only once.
     *
     * @return array{doc_logo: object, exact_copy: object, signature: object, editor: object, prepared: array{doc_logo_text_html: string, exact_copy_text_html: string, signature_text_html: string, date: DateTime, recipient_fields: array<int, string>, base64_logo: string}}
     */
    public static function prepareContext(MailMerge $mailmerge): array
    {
        $doc_logo = DB::table('doc_logos')->find($mailmerge->logo_id);
        $exact_copy = DB::table('exact_copies')->find($mailmerge->exact_copy_id);
        $signature = DB::table('signatures')->find($mailmerge->signature_id);
        $editor = DB::table('editors')->find($mailmerge->editor_id);

        $prepared = [
            'doc_logo_text_html' => preg_replace(
                '/\n/',
                "<br/>\n            ",
                $doc_logo->text,
            ),
            'exact_copy_text_html' => preg_replace('/\n/', '<br/>', $exact_copy->text),
            'signature_text_html' => preg_replace('/\n/', '<br/>', $signature->text),
            'date' => new DateTime($mailmerge->date),
            'recipient_fields' => json_decode($mailmerge->mergefields, true) ?? [],
            'base64_logo' => base64_encode(file_get_contents(public_path('images/'.$doc_logo->image))),
        ];

        return compact('doc_logo', 'exact_copy', 'signature', 'editor', 'prepared');
    }

    /**
     * Build the view data for a single record.
     *
     * @param  array{doc_logo: object, exact_copy: object, signature: object, editor: object, prepared: array<string, mixed>}  $context
     * @return array<string, mixed>
     */
    public static function viewData(MailMerge $mailmerge, array $context, array $record): array
    {
        return [
            'mailmerge' => $mailmerge,
            'doc_logo' => $context['doc_logo'],
            'exact_copy' => $context['exact_copy'],
            'signature' => $context['signature'],
            'editor' => $context['editor'],
            'record' => $record,
            'prepared' => $context['prepared'],
        ];
    }

    /**
     * Build the archive entry name for a single record.
     *
     * @param  array<int, string>  $mergefields
     * @param  array<int, string>  $recipient_names
     * @param  array<int, string>  $recipient_codes
     */
    public static function filename(
        MailMerge $mailmerge,
        array $record,
        int $index,
        array $mergefields,
        array $recipient_names,
        array $recipient_codes,
    ): string {
        $filename = $mailmerge->protocol_num.' '.$record['ΑΜ'].' '.($index + 1).' ';

        foreach ($mergefields as $mergefield) {
            $recipient_name = $record[$mergefield] ?? '';
            if ($recipient_name != '') {
                $filename .= $recipient_codes[array_search(StringConverter::removeAccents($recipient_name), $recipient_names)].' ';
            }
        }

        // Πέρα από τους παραλήπτες έλεγξε και το πεδίο του ατομικού φακέλου μήπως πρέπει να σταλεί πουθενά
        if (($record['ΑΦ'] ?? '') != '') {
            $filename .= $recipient_codes[array_search(StringConverter::removeAccents($record['ΑΦ']), $recipient_names)].' ';
        }

        if ($mailmerge->files_for_teachers) {
            $filename .= ' AM'.$record['ΑΜ'];
        }

        return $filename.'.pdf';
    }

    /**
     * Generate every PDF and collect it into a single zip archive.
     */
    public function handle(): void
    {
        $export = $this->export;
        $export->forceFill([
            'status' => MailMergeExport::STATUS_PROCESSING,
            'processed' => 0,
            'error' => null,
        ])->save();

        $mailmerge = $export->mailMerge;
        $records = json_decode($mailmerge->xlsxdata, true) ?? [];
        $export->forceFill(['total' => count($records)])->save();

        $context = self::prepareContext($mailmerge);
        $mergefields = json_decode($mailmerge->mergefields, true) ?? [];
        $recipient_names = DB::table('recipients')->pluck('name')->all();
        $recipient_codes = DB::table('recipients')->pluck('code')->all();

        Storage::makeDirectory('exports');
        $zip_path = 'exports/'.$export->id.'.zip';
        $zip = new ZipArchive;
        $zip->open(Storage::path($zip_path), ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($records as $index => $record) {
            $file = Pdf::view('apps.mailmerge.save', self::viewData($mailmerge, $context, $record))
                ->format('a4')
                ->margins(0, 0, 0, 0)
                ->generatePdfContent();

            $filename = self::filename($mailmerge, $record, $index, $mergefields, $recipient_names, $recipient_codes);
            $zip->addFromString($filename, $file);
            $zip->setCompressionName($filename, ZipArchive::CM_STORE);

            $export->forceFill(['processed' => $index + 1])->save();
        }

        $zip->close();

        $export->forceFill([
            'status' => MailMergeExport::STATUS_READY,
            'path' => $zip_path,
        ])->save();
    }

    /**
     * Clean up after a failed export.
     */
    public function failed(Throwable $exception): void
    {
        Storage::delete('exports/'.$this->export->id.'.zip');

        $this->export->forceFill([
            'status' => MailMergeExport::STATUS_FAILED,
            'error' => $exception->getMessage(),
        ])->save();
    }
}
