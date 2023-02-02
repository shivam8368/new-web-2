<?php
namespace App\Http\Controllers;

use App\Services\SettingsService;
use App\Services\VideosManagementService;
use App\Utils;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    private VideosManagementService $videosManagementService;
    private SettingsService $settingsService;

    public function __construct(VideosManagementService $videosManagementService, SettingsService $settingsService)
    {
        $this->videosManagementService = $videosManagementService;
        $this->settingsService = $settingsService;
    }

    public function home(int $page = 1): View
    {
        $homePage = $this->videosManagementService->homePage(
            $page,
            $this->settingsService->get('videosOrder'),
            VideosManagementService::getViewedHistory(),
            $this->settingsService->get('orientation')
        );

        if(is_object($homePage)) {
            $homeVideos = $homePage;
        }else{
            list($homeVideos, $recommendedVideos) = $homePage;
        }

        if($homeVideos->status === false) {
            Utils::fallback('Something went wrong on our side, please try again later.', 500);
        }

        $pagination = Utils::initializePagination($homeVideos->pagination);
        $pagination->setRange((new \Jenssegers\Agent\Agent())->isDesktop() ? 3 : 2);
        $pagination->setHref('/page/%d');

        return view('front.home', [
            'videos' => [
                'home' => $homeVideos->data,
                'recommended' => $recommendedVideos->data ?? []
            ],

            'pagination' => $pagination->draw()
        ]);
    }




}
