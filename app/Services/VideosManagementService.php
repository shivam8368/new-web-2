<?php
namespace App\Services;

use AdultVideosApi\AdultVideosApi;
use AdultVideosApi\Model\Request\Video\GetAllRequestModel;
use AdultVideosApi\Model\Request\Video\GetByIdRequestModel;
use AdultVideosApi\Model\Request\Video\GetOnlyBestRequestModel;
use AdultVideosApi\Model\Request\Video\GetRecommendedRequestModel;
use AdultVideosApi\Model\Request\Video\GetRelatedRequestModel;
use AdultVideosApi\Model\Request\Video\SearchRequestModel;
use App\Enums\OrientationsEnum;
use App\Enums\VideosOrderEnum;
use App\Utils;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;

class VideosManagementService
{
    private AdultVideosApi $adultVideosApi;
    private array $categoriesNameCache = [];

    public function __construct()
    {
        $this->adultVideosApi = new AdultVideosApi(env('APP_API_KEY'));
    }

    public function homePage(int $page, ?string $order, array $visitedVideos = [], ?string $orientation = null)
    {
        // Home videos
        $videosModel = env('API_ONLY_BEST')
            ? new GetOnlyBestRequestModel()
            : new GetAllRequestModel();

        $videosModel->page = $page;
        $videosModel->order = $order ?? VideosOrderEnum::TRENDING;
        $videosModel->sections = $orientation ?? OrientationsEnum::STRAIGHT;

        // Recommended videos on home page
        $recommendedVideosModel = new GetRecommendedRequestModel();
        $recommendedVideosModel->video_ids = count($visitedVideos)
            ? implode(',', $visitedVideos)
            : 'none';
        $recommendedVideosModel->per_page = env('API_PER_PAGE');
        $recommendedVideosModel->page = 1;
        $recommendedVideosModel->only_gay = $orientation == OrientationsEnum::GAY;
        $recommendedVideosModel->only_trans = $orientation == OrientationsEnum::TRANS;

        return $page == 1
            ? $this->get($videosModel, $recommendedVideosModel)
            : $this->get($videosModel);
    }

    public function recommendedPage(int $page, array $visitedVideos = [], ?string $orientation = null)
    {
        $recommendedVideosModel = new GetRecommendedRequestModel();
        $recommendedVideosModel->video_ids = count($visitedVideos)
            ? join(',', $visitedVideos)
            : 'none';
        $recommendedVideosModel->page = $page;
        $recommendedVideosModel->only_gay = $orientation == OrientationsEnum::GAY;
        $recommendedVideosModel->only_trans = $orientation == OrientationsEnum::TRANS;

        return $this->get($recommendedVideosModel);
    }

    public function videoViewPage(int $videoId)
    {
        // Video view
        $videoViewModel = new GetByIdRequestModel();
        $videoViewModel->video_ids = $videoId;

        // Related videos
        $relatedVideosModel = new GetRelatedRequestModel();
        $relatedVideosModel->video_id = $videoId;

        return $this->get($videoViewModel, $relatedVideosModel);
    }

    public function videoSearchPage(string $query, int $page)
    {
        $videoSearchModel = new SearchRequestModel();
        $videoSearchModel->query = $query;
        $videoSearchModel->order = VideosOrderEnum::TRENDING;
        $videoSearchModel->page = $page;

        return $this->get($videoSearchModel);
    }

    public function categoriesPage()
    {
        $allCategoriesModel = new \AdultVideosApi\Model\Request\Category\GetAllRequestModel();

        return $this->get($allCategoriesModel);
    }

    public function categoryViewPage(int $categoryId, int $page, ?string $order, ?string $orientation = null)
    {
        $videosModel = env('API_ONLY_BEST')
            ? new GetOnlyBestRequestModel()
            : new GetAllRequestModel();

        $videosModel->page = $page;
        $videosModel->order = $order ?? VideosOrderEnum::TRENDING;
        $videosModel->sections = $orientation ?? OrientationsEnum::STRAIGHT;
        $videosModel->categories = $categoryId;

        return $this->get($videosModel);
    }

    public function getVideosData(array $videoIds)
    {
        $videosDataModel = new GetByIdRequestModel();
        $videosDataModel->video_ids = join(',', $videoIds);

        return $this->get($videosDataModel);
    }

    public function myHistoryPage(array $videoIds)
    {
        $videoViewModel = new GetByIdRequestModel();
        $videoViewModel->video_ids = join(',', $videoIds);

        return $this->get($videoViewModel);
    }

    public static function getViewedHistory(): array
    {
        return array_filter(explode(',', Cookie::get('view_history', '')), 'ctype_digit');
    }

    public static function addToViewedHistory(int $videoId): void
    {
        $videos = array_slice(array_unique(array_filter(explode(
            ',',
            $videoId . ',' . Cookie::get('view_history', '')
        ), 'ctype_digit')), 0, 30);

        Cookie::queue('view_history', join(',', $videos), $minutes = 60*24*90);
    }

    public static function eraseViewedHistory(): void
    {
        Cookie::queue('view_history', '', $minutes = 60*24*90);
    }

    public function getCategoryNameById(int $categoryId): ?string
    {
        if(empty($this->categoriesNameCache) && Cache::has('categoriesCache')) {
            $this->categoriesNameCache = Cache::get('categoriesCache');
        }

        if(!array_key_exists($categoryId, $this->categoriesNameCache)) {
            $this->categoriesNameCache = $this->createCategoriesNameCache();
        }

        return $this->categoriesNameCache[$categoryId] ?? null;
    }


    private function createCategoriesNameCache(): array
    {
        $allCategoriesModel = new \AdultVideosApi\Model\Request\Category\GetAllRequestModel();
        $categoriesData = $this->get($allCategoriesModel);

        $categoriesCache = [];
        foreach($categoriesData->data as $categoryData) {
            $categoriesCache[$categoryData->id] = $categoryData->name;
        }

        Cache::forever('categoriesCache', $categoriesCache);
        return $categoriesCache;
    }

    private function get()
    {
        $models = func_get_args();
        if(!count($models)) {
            return null;
        }

        $responses = [];
        $modelsToFetch = [];

        foreach($models as $index => $model) {
            self::setModelDefaultProps($model);

            $responses[$index] = env('DB_USE')
                ? json_decode((string)ApiRequestCacheService::get(self::getModelEndpointUrl($model)))
                : null;

            if($responses[$index] === null) {
                $modelsToFetch[] = $model;
            }
        }

        if(count($modelsToFetch)) {
            $modelsResponses = $this->adultVideosApi->get(...$modelsToFetch);
            if(count($modelsToFetch) == 1) {
                $modelsResponses = [$modelsResponses];
            }

            foreach($responses as $index => $model) {
                if($model === null) {
                    $currentModel = $models[$index];
                    $currentModelResponse = current($modelsResponses);

                    if(!isset($currentModelResponse->status)) {
                        Utils::fallback('Unable to fetch data about videos, please try again later.', 500);
                    }

                    if($currentModelResponse->status === false && $currentModelResponse->error->code == 0) {
                        Utils::fallback('Unable to fetch data from Adult Videos API, provided wrong API Key.', 500);
                    }

                    if(env('DB_USE') && isset($currentModelResponse->status) && $currentModelResponse->status === true) {
                        ApiRequestCacheService::set(self::getModelEndpointUrl($currentModel), json_encode($currentModelResponse));
                    }

                    $responses[$index] = current($modelsResponses);
                    next($modelsResponses);
                }
            }
        }

        return count($responses) == 1
            ? current($responses)
            : $responses;
    }

    private static function setModelDefaultProps(&$model): void
    {
        if(property_exists($model, 'per_page') && !isset($model->per_page)) {
            $model->per_page = env('API_PER_PAGE');
        }

        if(property_exists($model, 'has_preview') && env('API_ONLY_VIDEOS_WITH_PREVIEW') && !isset($model->has_preview)) {
            $model->has_preview = true;
        }

        if(property_exists($model, 'title_alphabet') && !empty(env('API_ONLY_ALPHABET')) && !isset($model->title_alphabet)) {
            $model->title_alphabet = env('API_ONLY_ALPHABET');
        }

        if(property_exists($model, 'min_votes_pct') && env('API_MIN_VOTES_PCT') > 0 && !isset($model->min_votes_pct)) {
            $model->min_votes_pct = env('API_MIN_VOTES_PCT');
        }

        if(property_exists($model, 'only_best') && env('API_ONLY_BEST') && !isset($model->only_best)) {
            $model->only_best = env('API_ONLY_BEST');
        }
    }

    private static function getModelEndpointUrl($model): string
    {
        $modelVars = get_object_vars($model);
        array_walk($modelVars, function(&$paramValue){
            if(is_bool($paramValue)) {
                $paramValue = var_export($paramValue, true);
            }
        });

        $queryParams = http_build_query($modelVars);
        return AdultVideosApi::BASE_URI . $model::ENDPOINT_URI . '?' . $queryParams;
    }
}
