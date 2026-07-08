<?php

namespace App\Support;

class BrandAssets
{
    /**
     * Returns a data: URI for the crest, or null if the file isn't present
     * yet — callers should render nothing rather than let a missing asset
     * break PDF generation.
     */
    public static function logoDataUri(): ?string
    {
        $path = public_path('images/kiyovu-crest.png');

        if (! is_file($path)) {
            return null;
        }

        return 'data:image/png;base64,'.base64_encode(file_get_contents($path));
    }
}
