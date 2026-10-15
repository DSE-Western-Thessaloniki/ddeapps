<?php

use App\Jobs\GenerateMailMergeZip;
use App\Models\MailMerge\MailMergeExport;
use App\Option;
use App\User;
use Database\Seeders\OptionSeeder;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Facades\Pdf;

beforeEach(function (): void {
    $this->seed(OptionSeeder::class);
    $option = Option::where('name', 'first_run')->first();
    $option->value = 0;
    $option->save();
});

it('returns a pdf when printing a mail merge as admin', function (): void {
    $admin = User::factory()->admin()->create();
    $mailmerge = test_create_mailmerge_with_data_for_user($admin);

    Pdf::fake();

    $this->actingAs($admin)
        ->get(route('apps.mailmerge.print', $mailmerge->id))
        ->assertOk();

    Pdf::assertRespondedWithPdf(function ($pdf) use ($mailmerge): bool {
        return $pdf->viewName === 'apps.mailmerge.print'
            && $pdf->format === 'a4'
            && $pdf->margins === ['top' => 0.0, 'right' => 0.0, 'bottom' => 0.0, 'left' => 0.0, 'unit' => 'mm']
            && $pdf->viewData['mailmerge']->is($mailmerge);
    });
});

it('cannot print a mail merge as user', function (): void {
    $admin = User::factory()->admin()->create();
    $mailmerge = test_create_mailmerge_with_data_for_user($admin);
    $user = User::factory()->user()->create();

    Pdf::fake();

    $this->actingAs($user)
        ->get(route('apps.mailmerge.print', $mailmerge->id))
        ->assertForbidden();
});

it('starts a queued zip export when saving a mail merge as admin', function (): void {
    $admin = User::factory()->admin()->create();
    $mailmerge = test_create_mailmerge_with_data_for_user($admin);

    Queue::fake();

    $response = $this->actingAs($admin)
        ->post(route('apps.mailmerge.save', $mailmerge->id));

    $response->assertCreated();

    $records = json_decode($mailmerge->xlsxdata, true);
    $response->assertJsonStructure([
        'export_id',
        'status',
        'total',
        'processed',
        'status_url',
        'download_url',
    ]);
    $response->assertJson([
        'status' => MailMergeExport::STATUS_PENDING,
        'total' => count($records),
        'processed' => 0,
    ]);

    $export = MailMergeExport::find($response->json('export_id'));
    expect($export)->not->toBeNull()
        ->and($export->user_id)->toBe($admin->id)
        ->and($export->mail_merge_id)->toBe($mailmerge->id);

    Queue::assertPushed(
        GenerateMailMergeZip::class,
        fn (GenerateMailMergeZip $job): bool => $job->export->id === $export->id,
    );
});

it('cannot start a zip export as user', function (): void {
    $admin = User::factory()->admin()->create();
    $mailmerge = test_create_mailmerge_with_data_for_user($admin);
    $user = User::factory()->user()->create();

    Queue::fake();

    $this->actingAs($user)
        ->post(route('apps.mailmerge.save', $mailmerge->id))
        ->assertForbidden();

    Queue::assertNothingPushed();
});

it('redirects to login when starting a zip export unauthenticated', function (): void {
    $this->post('/apps/mailmerge/save/0')->assertRedirect(route('login'));
});

it('returns the export status', function (): void {
    $admin = User::factory()->admin()->create();
    $mailmerge = test_create_mailmerge_with_data_for_user($admin);
    $export = MailMergeExport::create([
        'user_id' => $admin->id,
        'mail_merge_id' => $mailmerge->id,
        'status' => MailMergeExport::STATUS_PROCESSING,
        'total' => 10,
        'processed' => 4,
    ]);

    $this->actingAs($admin)
        ->get(route('apps.mailmerge.exports.status', $export))
        ->assertOk()
        ->assertJson([
            'export_id' => $export->id,
            'status' => MailMergeExport::STATUS_PROCESSING,
            'total' => 10,
            'processed' => 4,
        ]);
});

it('cannot read the export status of another user', function (): void {
    $admin = User::factory()->admin()->create();
    $mailmerge = test_create_mailmerge_with_data_for_user($admin);
    $export = MailMergeExport::create([
        'user_id' => $admin->id,
        'mail_merge_id' => $mailmerge->id,
        'status' => MailMergeExport::STATUS_PENDING,
    ]);
    $user = User::factory()->user()->create();

    $this->actingAs($user)
        ->get(route('apps.mailmerge.exports.status', $export))
        ->assertForbidden();
});

it('downloads a ready export and removes it', function (): void {
    Storage::fake('local');

    $admin = User::factory()->admin()->create();
    $mailmerge = test_create_mailmerge_with_data_for_user($admin);
    $export = MailMergeExport::create([
        'user_id' => $admin->id,
        'mail_merge_id' => $mailmerge->id,
        'status' => MailMergeExport::STATUS_READY,
        'total' => 2,
        'processed' => 2,
        'path' => 'exports/1.zip',
    ]);
    Storage::put('exports/1.zip', 'zip-bytes');

    $this->actingAs($admin)
        ->get(route('apps.mailmerge.exports.download', $export))
        ->assertOk()
        ->assertDownload($mailmerge->protocol_num.'-'.$export->created_at->format('YmdHis').'.zip');

    expect(MailMergeExport::find($export->id))->toBeNull();
});

it('cannot download an export that is not ready yet', function (): void {
    $admin = User::factory()->admin()->create();
    $mailmerge = test_create_mailmerge_with_data_for_user($admin);
    $export = MailMergeExport::create([
        'user_id' => $admin->id,
        'mail_merge_id' => $mailmerge->id,
        'status' => MailMergeExport::STATUS_PROCESSING,
        'total' => 2,
        'processed' => 1,
    ]);

    $this->actingAs($admin)
        ->get(route('apps.mailmerge.exports.download', $export))
        ->assertStatus(409);
});

it('cannot download the export of another user', function (): void {
    $admin = User::factory()->admin()->create();
    $mailmerge = test_create_mailmerge_with_data_for_user($admin);
    $export = MailMergeExport::create([
        'user_id' => $admin->id,
        'mail_merge_id' => $mailmerge->id,
        'status' => MailMergeExport::STATUS_READY,
        'path' => 'exports/1.zip',
    ]);
    $user = User::factory()->user()->create();

    $this->actingAs($user)
        ->get(route('apps.mailmerge.exports.download', $export))
        ->assertForbidden();
});

it('builds a zip of pdfs when the export job runs', function (): void {
    Storage::fake('local');

    $admin = User::factory()->admin()->create();
    $mailmerge = test_create_mailmerge_with_data_for_user($admin);
    $export = MailMergeExport::create([
        'user_id' => $admin->id,
        'mail_merge_id' => $mailmerge->id,
        'status' => MailMergeExport::STATUS_PENDING,
    ]);

    Pdf::fake();

    (new GenerateMailMergeZip($export))->handle();

    $export->refresh();
    $records = json_decode($mailmerge->xlsxdata, true);
    expect($export->status)->toBe(MailMergeExport::STATUS_READY)
        ->and($export->processed)->toBe(count($records))
        ->and($export->total)->toBe(count($records))
        ->and($export->path)->toBe('exports/'.$export->id.'.zip')
        ->and($export->error)->toBeNull();

    Pdf::assertViewIs('apps.mailmerge.save');

    $zip = new ZipArchive;
    expect($zip->open(Storage::path($export->path)))->toBeTrue();
    expect($zip->numFiles)->toBe(count($records));

    $firstEntry = $zip->getNameIndex(0);
    $zip->close();
    expect($firstEntry)->toContain($mailmerge->protocol_num)
        ->and($firstEntry)->toEndWith('.pdf');
});

it('marks the export as failed when the job blows up', function (): void {
    Storage::fake('local');

    $admin = User::factory()->admin()->create();
    $mailmerge = test_create_mailmerge_with_data_for_user($admin);
    $mailmerge->logo_id = 999999;
    $mailmerge->save();
    $export = MailMergeExport::create([
        'user_id' => $admin->id,
        'mail_merge_id' => $mailmerge->id,
        'status' => MailMergeExport::STATUS_PENDING,
    ]);

    Pdf::fake();

    $job = new GenerateMailMergeZip($export);
    try {
        $job->handle();
    } catch (Throwable $exception) {
        $job->failed($exception);
    }

    $export->refresh();
    expect($export->status)->toBe(MailMergeExport::STATUS_FAILED)
        ->and($export->error)->not->toBeNull();
});

it('renders the save template with the precomputed context', function (): void {
    $admin = User::factory()->admin()->create();
    $mailmerge = test_create_mailmerge_with_data_for_user($admin);
    $records = json_decode($mailmerge->xlsxdata, true);

    $context = GenerateMailMergeZip::prepareContext($mailmerge);
    $html = view(
        'apps.mailmerge.save',
        GenerateMailMergeZip::viewData($mailmerge, $context, $records[0]),
    )->render();

    expect($html)->toContain($mailmerge->protocol_num);
});

it('generates a real pdf through the configured driver', function (): void {
    $admin = User::factory()->admin()->create();
    $mailmerge = test_create_mailmerge_with_data_for_user($admin);

    $response = $this->actingAs($admin)
        ->get(route('apps.mailmerge.print', $mailmerge->id));

    $response->assertOk();
    expect($response->getContent())->toContain('%PDF');
})->skip(fn (): bool => config('laravel-pdf.driver') !== 'gotenberg'
    || @file_get_contents(config('laravel-pdf.gotenberg.url').'/health') === false, 'Gotenberg is not reachable');
