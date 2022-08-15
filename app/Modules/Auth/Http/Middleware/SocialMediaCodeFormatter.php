<?php


namespace Modules\Auth\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Modules\Auth\Services\SocialMedia\Handlers\GoogleHandler;

class SocialMediaCodeFormatter
{
    public function handle(Request $request, Closure $next)
    {
        $provider = $request->route('provider');
        if ($provider == GoogleHandler::PROVIDER_NAME) {
            $code = $request->get('code');
            if ($code) {
                $request = $request->merge(['code' => urldecode($code)]);
            }
        }
        return $next($request);
    }
}
