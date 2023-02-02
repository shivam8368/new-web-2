<?php
namespace App\Http\Controllers;

use App\Services\SettingsService;
use App\Services\VideosManagementService;
use App\Utils;
use Illuminate\View\View;

class RecommendedController extends Controller
{
    private VideosManagementService $videosManagementService;
    private SettingsService $settingsService;

    public function __construct(VideosManagementService $videosManagementService, SettingsService $settingsService)
    {
        $this->videosManagementService = $videosManagementService;
        $this->settingsService = $settingsService;
    }

    public function recommended(int $page = 1): View
    {
        $viewedHistory = VideosManagementService::getViewedHistory();

        $recommendedVideos = $this->videosManagementService->recommendedPage(
            $page,
            $viewedHistory,
            $this->settingsService->get('orientation')
        );

        if($recommendedVideos->status === false) {
            Utils::fallback('Something went wrong on our side, please try again later.', 500);
        }

        $pagination = Utils::initializePagination($recommendedVideos->pagination);
        $pagination->setRange((new \Jenssegers\Agent\Agent())->isDesktop() ? 3 : 2);
        $pagination->setHref('/recommended/page/%d');

        return view('front.recommended', [
            'videos' => $recommendedVideos->data,
            'pagination' => $pagination->draw(),
            'viewedHistoryCount' => count($viewedHistory)
        ]);
    }

    public function recommendedReset()
    {
        VideosManagementService::eraseViewedHistory();
        return redirect('/recommended');
    }
}
