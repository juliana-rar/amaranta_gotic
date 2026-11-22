<?php

use App\Models\BusinessHour;
use App\Models\SiteContent;
use App\Models\SiteText;
use Illuminate\Support\Facades\Cache;

if (! function_exists('t')) {
    function t(string $key): string
    {
        $texts = Cache::rememberForever('site.texts', fn () => SiteText::pluck('value', 'key')->all());

        return (string) ($texts[$key] ?? $key);
    }
}

if (! function_exists('site_content')) {
    function site_content(): SiteContent
    {
        return Cache::rememberForever('site.content', fn () => SiteContent::first() ?? new SiteContent());
    }
}

if (! function_exists('day_names')) {
    function day_names(): array
    {
        return ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
    }
}

if (! function_exists('hours_text')) {
    function hours_text(BusinessHour $hour): string
    {
        if (! $hour->is_open || ! $hour->shift1_start || ! $hour->shift1_end) {
            return 'Cerrado';
        }

        $text = $hour->shift1_start.'–'.$hour->shift1_end;

        if ($hour->shift2_start && $hour->shift2_end) {
            $text .= ', '.$hour->shift2_start.'–'.$hour->shift2_end;
        }

        return $text;
    }
}

if (! function_exists('business_hours')) {
    function business_hours()
    {
        return Cache::rememberForever('site.hours', fn () => BusinessHour::orderBy('day_order')->get());
    }
}

if (! function_exists('address_lines')) {
    function address_lines(?string $address): array
    {
        return preg_split('/\r\n|\r|\n/', (string) $address, -1, PREG_SPLIT_NO_EMPTY) ?: [];
    }
}

if (! function_exists('reservation_slots')) {
    function reservation_slots(string $period): array
    {
        $start = $period === 'dinner' ? 20 * 60 : 13 * 60;
        $end = $period === 'dinner' ? 23 * 60 : 16 * 60;
        $slots = [];

        for ($minutes = $start; $minutes < $end; $minutes += 30) {
            $slots[] = sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60);
        }

        return $slots;
    }
}

if (! function_exists('upload_image')) {
    function upload_image($file, string $folder): string
    {
        $name = bin2hex(random_bytes(16)).'.'.strtolower($file->getClientOriginalExtension());
        $file->move(public_path('uploads'.DIRECTORY_SEPARATOR.$folder), $name);

        return '/uploads/'.$folder.'/'.$name;
    }
}
