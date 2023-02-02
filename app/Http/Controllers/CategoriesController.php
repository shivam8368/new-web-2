<?php

namespace App\Http\Controllers;

use App\Services\SettingsService;
use App\Services\VideosManagementService;
use App\Utils;
use Illuminate\View\View;

class CategoriesController extends Controller
{
    private VideosManagementService $videosManagementService;
    private SettingsService $settingsService;

    public function __construct(VideosManagementService $videosManagementService, SettingsService $settingsService)
    {
        $this->videosManagementService = $videosManagementService;
        $this->settingsService = $settingsService;
    }

    public function categories(): View
    {
        $categories = $this->videosManagementService->categoriesPage();

        if($categories->status === false) {
            Utils::fallback('Something went wrong on our side, please try again later.', 500);
        }

        usort($categories->data, fn($a, $b) => $a->name <=> $b->name);

        return view('front.categories', [
            'categories' => $categories->data
        ]);
    }

    public function categoryView(int $id, string $slug, int $page = 1): View
    {
        $videos = $this->videosManagementService->categoryViewPage(
            $id,
            $page,
            $this->settingsService->get('videosOrder'),
            $this->settingsService->get('orientation')
        );

        if($videos->status === false) {
            Utils::fallback('Something went wrong on our side, please try again later.', 500);
        }

        $pagination = Utils::initializePagination($videos->pagination);
        $pagination->setRange((new \Jenssegers\Agent\Agent())->isDesktop() ? 3 : 2);
        $pagination->setHref("/categories/{$id}-{$slug}/page/%d");

        return view('front.category-view', [
            'categoryName' => $this->videosManagementService->getCategoryNameById($id),
            'videos' => $videos->data,
            'pagination' => $pagination->draw()
        ]);
    }
}
