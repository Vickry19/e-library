<?php

namespace App\Helpers;

class AvatarHelper
{
    /**
     * Generate avatar inisial dari nama user
     * 
     * @param string $name
     * @return array [initial, color]
     */
    public static function generate($name)
    {
        if (empty($name)) {
            $name = 'User';
        }

        // Ambil huruf pertama dari 2 kata pertama
        $words = explode(' ', trim($name));
        $initial = strtoupper(substr($words[0], 0, 1));
        
        if (count($words) > 1) {
            $initial .= strtoupper(substr($words[1], 0, 1));
        }

        // Warna berdasarkan hash nama
        $colors = [
            '#007bff', // blue
            '#28a745', // green
            '#dc3545', // red
            '#ffc107', // yellow
            '#17a2b8', // cyan
            '#6f42c1', // purple
            '#fd7e14', // orange
            '#e83e8c', // pink
            '#20c997', // teal
            '#6610f2', // indigo
        ];
        
        $colorIndex = crc32($name) % count($colors);
        $color = $colors[abs($colorIndex)];

        return [
            'initial' => $initial,
            'color' => $color,
        ];
    }

    /**
     * Render avatar sebagai HTML
     */
    public static function render($name, $size = 40)
    {
        $data = self::generate($name);
        $fontSize = round($size * 0.4);
        
        return sprintf(
            '<div class="avatar-initial" style="width: %dpx; height: %dpx; background-color: %s; font-size: %dpx;">%s</div>',
            $size,
            $size,
            $data['color'],
            $fontSize,
            $data['initial']
        );
    }
}