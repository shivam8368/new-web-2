<?php
namespace App;

use App\Enums\OrientationsEnum;
use App\Enums\VideosOrderEnum;
use App\Services\PaginationService;
use App\Services\SettingsService;
use App\Services\VideosManagementService;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\NoReturn;

class Utils
{
    public static function initializePagination(\stdClass $pagination): PaginationService
    {
        $paginationService = new PaginationService();
        $paginationService->setCurrentPage($pagination->page);
        $paginationService->setTotalRecords($pagination->total);
        $paginationService->setRecordsPerPage($pagination->per_page);

        return $paginationService;
    }

    public static function numberShortener(int|float $number): string
    {
        if($number < 1000) {
            return $number;
        }

        if($number < 100000) {
            return round($number / 1000, 1) . 'K';
        }

        if($number < 1000000) {
            return round($number / 1000) . 'K';
        }

        if($number < 100000000) {
            return round($number / 1000000, 1) . 'M';
        }

        return round($number / 1000000) . 'M';
    }

    public static function videoDuration(int $seconds): string
    {
        if($seconds < 60) {
            return '0:'.($seconds < 10 ? '0'.$seconds : $seconds);
        }

        $minutes = floor($seconds / 60);
        $seconds = $seconds - ($minutes * 60);

        return $minutes.':'.($seconds < 10 ? '0'.$seconds : $seconds);
    }

    public static function getOrientationName(?string $orientation): string
    {
        return is_null($orientation)
            ? ucfirst(OrientationsEnum::STRAIGHT)
            : ucfirst($orientation);
    }

    public static function getVideosOrderName(?string $videosOrder): string
    {
        switch($videosOrder) {
            case VideosOrderEnum::VIEWS_COUNT:
                return 'Most Viewed';

            default: {
                return is_null($videosOrder)
                    ? ucfirst(VideosOrderEnum::TRENDING)
                    : ucfirst($videosOrder);
            }
        }
    }

    public static function assignCategoryNames(array &$categories, VideosManagementService $videosManagementService): void
    {
        $categoryNames = [];

        array_walk($categories, function($value) use (&$categoryNames, $videosManagementService){
            $categoryNames[$value] = $videosManagementService->getCategoryNameById($value);
        });

        $categories = $categoryNames;
    }

    public static function videoTitleSlug($videoTitle): string
    {
        $videoTitle = Str::slug($videoTitle);

        return strlen($videoTitle)
            ? $videoTitle
            : 'video';
    }

    #[NoReturn]
    public static function fallback(string $errorText, int $httpStatusCode = 200, VideosManagementService $videosManagementService = null, SettingsService $settingsService = null): void
    {
        $recommendedVideos = $videosManagementService?->recommendedPage(1, [], $settingsService?->get('orientation'));

        response()->view('front.fallback', [
            'videos' => $recommendedVideos->data ?? [],
            'errorText' => $errorText
        ])->setStatusCode($httpStatusCode)->send();

        exit;
    }
}
