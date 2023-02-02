<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiRequestCacheModel extends Model
{
    use HasFactory;

    protected $table = 'api_requests_cache';
    protected $fillable = ['request_hash', 'request_response'];
}
