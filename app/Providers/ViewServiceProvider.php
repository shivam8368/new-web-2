<?php
namespace App\Providers;

use App\Services\SettingsService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(SettingsService $settingsService): void
    {
        View::share('settingsService', $settingsService);
    }
}
