<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Class SiteController
 * @package App\Http\Controllers
 */
class SiteController extends Controller
{
    /**
     * @return RedirectResponse
     */
    public function index(): RedirectResponse
    {
        if (Auth::check()) {
            return redirect(route('user.index'));
        }

        return redirect(route('adminLogin'));
    }

    /**
     * @return string
     */
    public function apiDoc(): string
    {
        return file_get_contents(storage_path('postman/postman_collection.json'));
    }
}
