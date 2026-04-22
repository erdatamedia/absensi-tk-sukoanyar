<?php

namespace App\Support;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Schema;

class Branding
{
    public static function schoolName(): string
    {
        return self::setting('school_name', 'Absensi TK Sukoanyar');
    }

    public static function schoolTagline(): string
    {
        return self::setting('school_tagline', 'Sistem Absensi TK');
    }

    public static function operationalStart(): string
    {
        return self::setting('operational_start', env('ABSENSI_OPERASIONAL_MULAI', '06:30'));
    }

    public static function operationalEnd(): string
    {
        return self::setting('operational_end', env('ABSENSI_OPERASIONAL_SELESAI', '13:00'));
    }

    public static function logoPath(): ?string
    {
        $value = self::setting('school_logo_path');

        return $value !== '' ? $value : null;
    }

    public static function logoUrl(): ?string
    {
        $path = self::logoPath();

        return $path ? asset('storage/' . ltrim($path, '/')) : null;
    }

    public static function logoPublicPath(): ?string
    {
        $path = self::logoPath();

        if (! $path) {
            return null;
        }

        $fullPath = public_path('storage/' . ltrim($path, '/'));

        return is_file($fullPath) ? $fullPath : null;
    }

    public static function initials(): string
    {
        $parts = preg_split('/\s+/', trim(self::schoolName())) ?: [];
        $initials = '';

        foreach ($parts as $part) {
            if ($part === '') {
                continue;
            }

            $initials .= mb_substr($part, 0, 1);

            if (mb_strlen($initials) >= 2) {
                break;
            }
        }

        return strtoupper($initials ?: 'AT');
    }

    public static function forgetCache(): void
    {
        static::$cache = null;
    }

    protected static array|null $cache = null;

    protected static function setting(string $key, string $default = ''): string
    {
        if (! Schema::hasTable('app_settings')) {
            return $default;
        }

        if (static::$cache === null) {
            static::$cache = AppSetting::query()->pluck('value', 'key')->all();
        }

        return (string) (static::$cache[$key] ?? $default);
    }
}
