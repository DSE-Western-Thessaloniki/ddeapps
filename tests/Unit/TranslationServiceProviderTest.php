<?php

use App\Providers\TranslationServiceProvider;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

uses(TestCase::class);

it('caches translations for every supported locale', function (): void {
    $translations = cache('translations');

    expect($translations)->toBeArray()
        ->toHaveKey('el')
        ->toHaveKey('en');

    expect($translations['el']['json'])->toBeArray()->not->toBeEmpty()
        ->and($translations['el']['php'])->toHaveKey('auth');
});

it('rebuilds an empty cached translations payload', function (): void {
    Cache::forever('translations', []);

    (new TranslationServiceProvider(app()))->boot();

    expect(cache('translations'))->toHaveKey('el');
});
