<?php

use App\Services\StringConverter;

describe('removeAccents', function (): void {
    it('strips Greek accents including eta and omega', function (): void {
        expect(StringConverter::removeAccents('ΛΈΞΗ'))->toBe('ΛΕΞΗ')
            ->and(StringConverter::removeAccents('ΛΕΞΉ'))->toBe('ΛΕΞΗ')
            ->and(StringConverter::removeAccents('ΛΕΞΏ'))->toBe('ΛΕΞΩ')
            ->and(StringConverter::removeAccents('λεξή'))->toBe('λεξη')
            ->and(StringConverter::removeAccents('λεξώ'))->toBe('λεξω');
    });

    it('strips the pre-existing accented characters', function (): void {
        expect(StringConverter::removeAccents('ΆΈΊΌΎΪΫ'))->toBe('ΑΕΙΟΥΙΥ')
            ->and(StringConverter::removeAccents('άέίόύϊΐΰ'))->toBe('αειουιιυ');
    });
});

describe('namesMatch', function (): void {
    it('matches names differing only in tonos', function () {
        expect(StringConverter::namesMatch('ΛΕΞΗ', 'ΛΈΞΗ'))->toBeTrue();
    });

    it('matches names differing only in case', function () {
        expect(StringConverter::namesMatch('ΛΕΞΗ', 'λεξη'))->toBeTrue();
    });

    it('matches iota with and without dialytika', function () {
        expect(StringConverter::namesMatch('ΙΩΑΝΝΗΣ', 'Ιωάννης'))->toBeTrue()
            ->and(StringConverter::namesMatch('ΚΑΛΑΙΣΚΟΥ', 'Καλαΐσκου'))->toBeTrue();
    });

    it('matches final sigma with plain sigma', function () {
        expect(StringConverter::namesMatch('πετρος', 'πετροσ'))->toBeTrue()
            ->and(StringConverter::namesMatch('οδός', 'οδοσ'))->toBeTrue();
    });

    it('matches names with trailing whitespace differences', function () {
        expect(StringConverter::namesMatch('ΛΕΞΗ ', 'ΛΕΞΗ'))->toBeTrue()
            ->and(StringConverter::namesMatch('ΛΕΞΗ', "ΛΕΞΗ\n"))->toBeTrue()
            ->and(StringConverter::namesMatch(' ΛΕΞΗ', 'ΛΕΞΗ'))->toBeTrue();
    });

    it('does not match Greek omicron epsilon against latin lookalikes', function () {
        expect(StringConverter::namesMatch('ΛΕΞΗ', 'ΛEXH'))->toBeFalse();
    });

    it('does not match hyphen against space', function () {
        expect(StringConverter::namesMatch('ΠΑΠΑ-ΔΟΠΟΥΛΟΣ', 'ΠΑΠΑ ΔΟΠΟΥΛΟΣ'))->toBeFalse();
    });

    it('does not match hyphen-less variants', function () {
        expect(StringConverter::namesMatch('ΠΑΠΑΔΟΠΟΥΛΟΣ', 'ΠΑΠΑ-ΔΟΠΟΥΛΟΣ'))->toBeFalse();
    });

    it('does not match differing internal whitespace', function () {
        expect(StringConverter::namesMatch('Α Β', 'ΑΒ'))->toBeFalse();
    });
});
