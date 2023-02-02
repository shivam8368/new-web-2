<?php
namespace App\Http\Controllers;

use App\Enums\OrientationsEnum;
use App\Enums\VideosOrderEnum;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function setOrientation(Request $request, string $orientation)
    {
        $reflection = new \ReflectionClass(OrientationsEnum::class);
        if(!in_array($orientation, $reflection->getConstants())) {
            return redirect('/');
        }

        SettingsService::set('orientation', $orientation);

        return redirect($request->query('next', '/'));
    }

    public function setOrientationBoxClosed()
    {
        SettingsService::set('orientation-box-closed', true);
    }

    public function setVideosOrder(Request $request, string $videosOrder)
    {
        $reflection = new \ReflectionClass(VideosOrderEnum::class);
        if(!in_array($videosOrder, $reflection->getConstants())) {
            return redirect('/');
        }

        SettingsService::set('videosOrder', $videosOrder);

        return redirect($request->query('next', '/'));
    }

}
