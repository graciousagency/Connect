<?php


namespace Robin\Connect\ServiceProviders;


use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Robin\Support\Contracts\Retriever;
use Robin\Connect\SEOShop\SEOShop;
use Robin\Api\Robin;
use Robin\Support\Contracts\Sender;

class RobinConnectSEOShopServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton(
            'WebshopappApiClient',
            function () {
                $key = env("SEOSHOP_API_KEY");
                $secret = env("SEOSHOP_API_SECRET");
                $language = env("SEOSHOP_API_LANGUAGE");

                return new \WebshopappApiClient('live', $key, $secret, $language);
            }
        );

        $this->app->bind(Sender::class, Robin::class);

        $this->app->bind(
            Robin::class,
            function () {
                $key = env("ROBIN_API_KEY");
                $secret = env("ROBIN_API_SECRET");
                $url = env("ROBIN_API_URL");

                return new Robin($key, $secret, $url);
            }
        );

        $this->app->bind(
            Retriever::class,
            SEOShop::class
        );
        $this->app->bind(
            SEOShop::class,
            function (Application $application) {
                /** @var \WebshopappApiClient $webshopappApiClient */
                $webshopappApiClient = $application->make('WebshopappApiClient');

                return new SEOShop($webshopappApiClient);
            }
        );
    }
}