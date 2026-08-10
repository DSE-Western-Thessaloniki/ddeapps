<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Cache::rememberForever('translations', function () {
            $translations = collect();
            $locales = array_map(
                fn ($dir) => basename($dir), glob('../resources/lang/*', GLOB_ONLYDIR)
            );

            foreach ($locales as $locale) { // supported locales
                $translations[$locale] = [
                    'php' => $this->phpTranslations($locale)->toArray(),
                    'json' => $this->jsonTranslations($locale),
                ];
            }

            return $translations->toArray();
        });
    }

    private function phpTranslations($locale)
    {
        $path = resource_path("lang/$locale");

        return collect(File::allFiles($path))->flatMap(function ($file) use ($locale) {
            $key = ($translation = $file->getBasename('.php'));

            return [$key => trans($translation, [], $locale)];
        });
    }

    private function jsonTranslations($locale)
    {
        $path = resource_path("lang/$locale.json");

        if (is_string($path) && is_readable($path)) {
            return json_decode(file_get_contents($path), true);
        }

        return [];
    }
}
