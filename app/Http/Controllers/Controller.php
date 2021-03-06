<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOTools;
use Closure;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function setSeo(array $params = []): void
    {
        $defaults = [
            'title'       => '',
            'description' => '',
        ];

        $params = array_merge($defaults, $params);

        if (!empty($params['title'])) {
            SEOTools::setTitle($params['title']);
        }

        $description = Str::limit(strip_tags($params['description']), 170);
        if (!empty($description)) {
            SEOTools::setDescription($description);
        }
    }

    protected function withCache(string $key, Closure $callback)
    {
        return Cache::remember($key, config('cache.ttl'), $callback);
    }

    protected function getCurrentPageNumber(Request $request): string
    {
        return $request->get('page') ?? 1;
    }
}
