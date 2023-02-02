<?php
namespace App\Http\Controllers;

use App\Services\VideosManagementService;
use App\Utils;
use Illuminate\Http\Response;

class SearchController extends Controller
{
    private VideosManagementService $videosManagementService;

    public function __construct(VideosManagementService $videosManagementService)
    {
        $this->videosManagementService = $videosManagementService;
    }

    public function search(string $query, int $page = 1): Response
    {
        $videos = $this->videosManagementService->videoSearchPage($query, $page);

        if($videos->status === false) {
            Utils::fallback('Something went wrong on our side, please try again later.', 500);
        }

        $pagination = Utils::initializePagination($videos->pagination);
        $pagination->setRange((new \Jenssegers\Agent\Agent())->isDesktop() ? 3 : 2);
        $pagination->setHref('/search/'.urlencode($query).'/page/%d');

        $alternatives = [];
        if(!count($videos->data)) {
            $alternatives = $this->videosManagementService->getVideosData($videos->alternatives);
        }

        return response()->view('front.search', [
            'videos' => $videos->data,
            'alternatives' => $alternatives->data ?? [],
            'pagination' => $pagination->draw(),
            'searchInputValue' => $query
        ])->setStatusCode(count($videos->data) ? 200 : 404);
    }
}
