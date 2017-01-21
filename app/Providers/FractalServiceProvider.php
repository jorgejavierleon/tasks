<?php


namespace App\Providers;


use App\Http\Responses\FractalResponse;
use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;

class FractalServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'League\Fractal\Serializer\SerializerAbstract',
            'League\Fractal\Serializer\DataArraySerializer'
        );

        $this->app->bind(FractalResponse::class, function($app) {
            $manager = new Manager();
            $serializer = $app['League\Fractal\Serializer\SerializerAbstract'];
            return new FractalResponse($manager, $serializer);
        });
    }
}