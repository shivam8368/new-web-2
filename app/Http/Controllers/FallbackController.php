<?php

namespace App\Http\Controllers;

use App\Services\SettingsService;
use App\Services\VideosManagementService;
use App\Utils;
use JetBrains\PhpStorm\NoReturn;

class FallbackController extends Controller
{
    private VideosManagementService $videosManagementService;
    private SettingsService $settingsService;

    public function __construct(VideosManagementService $videosManagementService, SettingsService $settingsService)
    {
        $this->videosManagementService = $videosManagementService;
        $this->settingsService = $settingsService;
    }

    #[NoReturn]
    public function __invoke(): void
    {
        Utils::fallback('URL Address you looking for does not exists.', 404, $this->videosManagementService, $this->settingsService);
    }
}
