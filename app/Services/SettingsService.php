<?php
namespace App\Services;

use Illuminate\Support\Facades\Cookie;

class SettingsService
{
    private ?array $settingsCache = null;

    public static function set(string $key, mixed $value): void
    {
        $settings = json_decode(Cookie::get('settings') ?? '[]', true);
        $settings[$key] = $value;
        Cookie::queue('settings', json_encode($settings), 60*24*365);
    }

    public function get(string $key = null): string|array|null
    {
        if(is_null($this->settingsCache)) {
            $this->settingsCache = json_decode(Cookie::get('settings') ?? '[]', true);
        }

        return !is_null($key)
            ? $this->settingsCache[$key] ?? null
            : $this->settingsCache;
    }
}
