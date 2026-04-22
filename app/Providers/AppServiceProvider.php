<?php

namespace App\Providers;

use App\Support\Branding;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::share('schoolBranding', [
            'name' => Branding::schoolName(),
            'tagline' => Branding::schoolTagline(),
            'logo_url' => Branding::logoUrl(),
            'initials' => Branding::initials(),
        ]);
    }
}
