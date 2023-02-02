<?php

use App\Enums\MenuItemsEnum;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Home
Route::group(['activeMenuItem' => MenuItemsEnum::Home], function(){
    Route::controller(\App\Http\Controllers\HomeController::class)->group(function() {
        Route::get('/', 'home')->name('menu_'.MenuItemsEnum::Home->name);
        Route::get('/page/{page}', 'home')->where([
            'page' => '\d+'
        ]);
    });
});

// Video View
Route::controller(\App\Http\Controllers\VideoViewController::class)->group(function() {
    Route::get('/video/{id}-{slug}', 'videoView')->where([
        'id' => '\d+'
    ])->name('videoView');
});

// Categories
Route::group(['activeMenuItem' => MenuItemsEnum::Categories], function() {
    Route::controller(\App\Http\Controllers\CategoriesController::class)->group(function () {
        Route::get('/categories', 'categories')->name('menu_'.MenuItemsEnum::Categories->name);
        Route::get('/categories/{id}-{slug}', 'categoryView')->name('categoryView')->where([
            'id' => '\d+',
        ]);

        Route::get('/categories/{id}-{slug}/page/{page}', 'categoryView')->where([
            'id' => '\d+',
            'page' => '\d+'
        ]);
    });
});

// Recommended
Route::group(['activeMenuItem' => MenuItemsEnum::Recommended], function() {
    Route::controller(\App\Http\Controllers\RecommendedController::class)->group(function () {
        Route::get('/recommended', 'recommended')->name('menu_'.MenuItemsEnum::Recommended->name);
        Route::get('/recommended/reset', 'recommendedReset')->name('recommendedReset');
        Route::get('/recommended/page/{page}', 'recommended')->where([
            'page' => '\d+'
        ]);
    });
});

// My History
Route::group(['activeMenuItem' => MenuItemsEnum::MyHistory], function() {
    Route::controller(\App\Http\Controllers\MyHistoryController::class)->group(function () {
        Route::get('/my-history', 'myHistory')->name('menu_'.MenuItemsEnum::MyHistory->name);
        Route::get('/my-history/page/{page}', 'myHistory')->where([
            'page' => '\d+'
        ]);

        // Ajax Requests
        Route::middleware(\App\Http\Middleware\OnlyAjax::class)->group(function () {
            Route::post('/my-history/get-videos-content', 'getVideosContent')->name('my-history.videos-content');
        });
    });
});


// Search
Route::controller(\App\Http\Controllers\SearchController::class)->group(function() {
    Route::get('/search/{query}', 'search');
    Route::get('/search/{query}/page/{page}', 'search')->where([
        'page' => '\d+'
    ]);;
});

// Settings
Route::controller(\App\Http\Controllers\SettingsController::class)->group(function(){
    Route::get('/set/orientation/{orientation}', 'setOrientation')->where([
        'orientation' => '^[a-zA-Z-]*$'
    ])->name('setOrientation');

    // Ajax Requests
    Route::middleware(\App\Http\Middleware\OnlyAjax::class)->group(function () {
        Route::get('/set/orientation-box-closed', 'setOrientationBoxClosed')->name('setOrientationBoxClosed');
    });

    Route::get('/set/videos-order/{videosOrder}', 'setVideosOrder')->where([
        'videosOrder' => '^[a-zA-Z-]*$'
    ])->name('setVideosOrder');
});


// Fallback
Route::fallback(\App\Http\Controllers\FallbackController::class);
