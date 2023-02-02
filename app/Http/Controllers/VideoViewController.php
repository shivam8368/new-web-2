<?php
namespace App\Http\Controllers;

use App\Enums\OrientationsEnum;
use App\Services\SettingsService;
use App\Services\VideosManagementService;
use App\Utils;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class VideoViewController extends Controller
{
    private VideosManagementService $videosManagementService;
    private SettingsService $settingsService;

    public function __construct(VideosManagementService $videosManagementService, SettingsService $settingsService)
    {
        $this->videosManagementService = $videosManagementService;
        $this->settingsService = $settingsService;
    }

    public function videoView(int $id): View
    {
        if(!Cache::has('not-found-'.$id)) {
            list($videoData, $relatedVideos) = $this->videosManagementService->videoViewPage($id);
        }

        if(!isset($videoData, $relatedVideos) || $relatedVideos->status === false) {
            if(!Cache::has('not-found-'.$id)) {
                Cache::add('not-found-'.$id, '', 60*60*3);
            }

            Utils::fallback('Video not found, probably was deleted or hidden.', 404, $this->videosManagementService, $this->settingsService);
        }

        $videoData = current($videoData->data);

        VideosManagementService::addToViewedHistory($id);
        Utils::assignCategoryNames($videoData->categories, $this->videosManagementService);

        if(is_null($this->settingsService->get('orientation-box-closed')) && count($videoData->sections) == 1 && ($this->settingsService->get('orientation') ?? OrientationsEnum::STRAIGHT) != $videoData->sections[0]) {
            $differentVideoOrientation = [
                'current' => $this->settingsService->get('orientation') ?? OrientationsEnum::STRAIGHT,
                'new' => $videoData->sections[0]
            ];
        }

        return view('front.video-view', [
            'videoData' => $videoData,
            'relatedVideos' => $relatedVideos->data,
            'differentVideoOrientation' => $differentVideoOrientation ?? false
        ]);
    }
}
