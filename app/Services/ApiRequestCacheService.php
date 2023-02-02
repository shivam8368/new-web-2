<?php
namespace App\Services;

use App\Models\ApiRequestCacheModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ApiRequestCacheService
{
    public static function get(string $uri): ?string
    {
        self::recycle();

        $requestHash = md5($uri);
        return ApiRequestCacheModel::where('request_hash', $requestHash)->first()->request_response ?? null;
    }

    public static function set(string $uri, string $apiResponse): void
    {
        $requestHash = md5($uri);

        DB::table('api_requests_cache')->insertOrIgnore([
            'request_hash' => $requestHash,
            'request_response' => $apiResponse,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
    }


    private static function recycle()
    {
        ApiRequestCacheModel::where('created_at', '<=', Carbon::now()->subHours(2))->delete();
    }
}
