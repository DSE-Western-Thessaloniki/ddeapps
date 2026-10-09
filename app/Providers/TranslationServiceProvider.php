<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $translations = Cache::get('translations');

        if (! is_array($translations) || $translations === []) {
            Cache::forever('translations', $this->buildTranslations());
        }
    }

    /**
     * Build the translations payload for every supported locale.
     *
     * @return array<string, array{php: array<string, string|array>, json: array<string, string>}>
     */
    private function buildTranslations(): array
    {
        $translations = [];

        foreach ($this->locales() as $locale) {
            $translations[$locale] = [
                'php' => $this->phpTranslations($locale),
                'json' => $this->jsonTranslations($locale),
            ];
        }

        return $translations;
    }

    /**
     * Get the supported locales from the lang directory.
     *
     * @return array<int, string>
     */
    private function locales(): array
    {
        return array_map(
            fn ($dir) => basename($dir), glob(lang_path('/*'), GLOB_ONLYDIR)
        );
    }

    /**
     * Get the group translation files of the given locale.
     *
     * @return array<string, string|array>
     */
    private function phpTranslations(string $locale): array
    {
        $path = lang_path($locale);

        return collect(File::allFiles($path))->flatMap(function ($file) use ($locale) {
            $key = ($translation = $file->getBasename('.php'));

            return [$key => trans($translation, [], $locale)];
        })->toArray();
    }

    /**
     * Get the JSON translations of the given locale.
     *
     * @return array<string, string>
     */
    private function jsonTranslations(string $locale): array
    {
        $path = lang_path("$locale.json");

        if (is_readable($path)) {
            return json_decode(file_get_contents($path), true) ?: [];
        }

        return [];
    }
}
