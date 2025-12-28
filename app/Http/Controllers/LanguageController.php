<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class LanguageController extends Controller
{
    /**
     * Switch language
     */
    public function switch(Request $request, $locale)
    {
        if (in_array($locale, ['en', 'ar'])) {
            Session::put('locale', $locale);
        }
        
        return redirect()->back();
    }
}
