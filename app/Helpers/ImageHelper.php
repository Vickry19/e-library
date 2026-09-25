<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Get image URL with fallback to default
     */
    public static function url($path, $type = 'profil-pic')
    {
        $default = $type . '/default.jpg';
        
        if (empty($path) || !file_exists(storage_path('app/public/' . $path))) {
            $path = $default;
        }

        return asset('storage/' . $path);
    }
}