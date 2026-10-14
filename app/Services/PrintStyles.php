<?php

namespace App\Services;

class PrintStyles
{
    /**
     * Return the compiled print stylesheet from the Vite manifest, so print layouts
     * can inline it. PDF renderers such as Gotenberg cannot reliably reach the
     * application's asset host, so print pages must not depend on linked assets.
     */
    public static function css(): string
    {
        $manifest = public_path('build/manifest.json');

        if (! is_file($manifest)) {
            return '';
        }

        $entries = json_decode((string) file_get_contents($manifest), true);
        $file = $entries['resources/sass/app.scss']['file'] ?? null;

        if (! is_string($file) || ! is_file($path = public_path('build/'.$file))) {
            return '';
        }

        return (string) file_get_contents($path);
    }
}
