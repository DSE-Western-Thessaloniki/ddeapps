<?php

use App\Option;
use App\User;
use Database\Seeders\OptionSeeder;
use Spatie\LaravelPdf\Facades\Pdf;
use ZipArchive;

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

it('returns a zip of pdfs when saving a mail merge as admin', function (): void {
    $admin = User::factory()->admin()->create();
    $mailmerge = test_create_mailmerge_with_data_for_user($admin);

    Pdf::fake();

    $response = $this->actingAs($admin)
        ->get(route('apps.mailmerge.save', $mailmerge->id))
        ->assertOk();

    $records = json_decode($mailmerge->xlsxdata, true);
    Pdf::assertViewIs('apps.mailmerge.save');

    $zip = new ZipArchive;
    $zipPath = $response->baseResponse->getFile()->getPathname();
    expect($zip->open($zipPath))->toBeTrue();
    expect($zip->numFiles)->toBe(count($records));

    $firstEntry = $zip->getNameIndex(0);
    $zip->close();
    expect($firstEntry)->toContain($mailmerge->protocol_num)
        ->and($firstEntry)->toEndWith('.pdf');
});

it('cannot save a mail merge as user', function (): void {
    $admin = User::factory()->admin()->create();
    $mailmerge = test_create_mailmerge_with_data_for_user($admin);
    $user = User::factory()->user()->create();

    Pdf::fake();

    $this->actingAs($user)
        ->get(route('apps.mailmerge.save', $mailmerge->id))
        ->assertForbidden();
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
