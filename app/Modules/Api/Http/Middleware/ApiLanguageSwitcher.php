<?php

namespace Modules\Api\Http\Middleware;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Modules\Language\Services\LanguageService;
use Modules\User\Models\User;

class ApiLanguageSwitcher
{
    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     * @throws BindingResolutionException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $languageService = app()->make(LanguageService::class);
        $code = $request->headers->get('content-language');

        // get lang in session user
        if (!$code && $user = $request->user()) {
            /** @var User $user */
            $code = $user->lang;
        }

        // check supported
        if (!$languageService->languageExistByCode($code)) {
            $code = null;
        }

        // default languages
        if (!$code) {
            $code = config('app.locale');
        }

        App::setLocale(Str::lower($code));
        return $next($request);
    }
}
