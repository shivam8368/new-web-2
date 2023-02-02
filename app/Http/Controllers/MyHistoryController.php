<?php

namespace App\Http\Controllers;

use App\Services\VideosManagementService;
use App\Utils;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class MyHistoryController extends Controller
{
    private VideosManagementService $videosManagementService;

    public function __construct(VideosManagementService $videosManagementService)
    {
        $this->videosManagementService = $videosManagementService;
    }

    public function myHistory(int $page = 1): View
    {
        return view('front.my-history', [
            'page' => $page,
        ]);
    }

    public function getVideosContent(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'page' => 'numeric|min:1',
            'videosHistory' => [
                'required',
                'regex:~^[0-9,]*$~'
            ]
        ]);

        if($validator->fails()) {
            exit;
        }

        $currentPage = $request->input('page');
        $videosPerPageCount = env('API_PER_PAGE');

        $videosHistory = array_filter(explode(',', $request->input('videosHistory')));
        $videoIds = array_slice($videosHistory, ($currentPage-1) * $videosPerPageCount, $videosPerPageCount);

        $videosData = null;
        if(count($videoIds) && $request->input('videosHistory') != '0') {
            $videosData = $this->videosManagementService->myHistoryPage($videoIds);

            if($videosData->status === false) {
                return response('', 500);
            }
        }

        $paginationProps = new \stdClass();
        $paginationProps->page = $currentPage;
        $paginationProps->per_page = $videosPerPageCount;
        $paginationProps->total = count($videosHistory);


        $pagination = Utils::initializePagination($paginationProps);
        $pagination->setRange((new \Jenssegers\Agent\Agent())->isDesktop() ? 3 : 2);
        $pagination->setHref('/my-history/page/%d');

        return response()->view('front.partials.my-history.videos-content', [
            'videos' => $videosData->data ?? [],
            'pagination' => $pagination->draw()
        ]);
    }
}
