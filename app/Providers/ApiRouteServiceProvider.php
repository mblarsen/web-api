<?php

namespace App\Providers;

use App\Services\ApiRoute;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class ApiRouteServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Map all Api Routes here
     *
     */
    public function map()
    {
        $this->mapRoutes('users', 'UserController');
        $this->mapRoutes('artists', 'ArtistController');
        $this->mapRoutes('albums', 'AlbumController');
        $this->mapRoutes('flacfiles', 'FlacFileController');
        $this->mapRoutes('skus', 'SkuController');
        $this->mapRoutes('songs', 'SongController');
        // ...
    }

    private function mapRoutes($prefix, $controller)
    {
        $dto = new ApiRoute($prefix, $controller);
        $dto->mapDefaultRoutes();
        return $dto;
    }
}