<?php

namespace App\Http\Middleware;

use App\Services\ContentModerator;
use Closure;
use Illuminate\Http\Request;

class ModerateText
{
    public function __construct(private ContentModerator $moderator) {}

    public function handle(Request $request, Closure $next)
    {
        foreach (['content_P', 'content_C'] as $field) {
            if ($request->has($field)) {
                $res = $this->moderator->moderate($request->input($field));
                $request->merge([$field => $res['clean']]);
                // you could also attach $res to the request for logging if needed
            }
        }
        return $next($request);
    }
}
