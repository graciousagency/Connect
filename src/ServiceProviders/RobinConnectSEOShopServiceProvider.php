<?php


namespace Robin\Connect\ServiceProviders;


use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Monolog\Logger;
use Robin\Api\Logger\RobinLogger;
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
        $this->bindContracts();

        $this->registerSingletons();

        $this->app->singleton(
            RobinLogger::class,
            function () {
                return new RobinLogger(new Logger("ROBIN"));
            }
        );

    }

    private function registerSingletons()
    {
        $this->registerSEOShop();

        $this->registerRobin();
    }

    private function registerSEOShop()
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

        $this->app->singleton(
            SEOShop::class,
            function (\WebshopappApiClient $webshopappApiClient) {
                return new SEOShop($webshopappApiClient);
            }
        );
    }

    private function registerRobin()
    {
        $this->app->singleton(
            Robin::class,
            function () {
                $key = env("ROBIN_API_KEY");
                $secret = env("ROBIN_API_SECRET");
                $url = env("ROBIN_API_URL");

                return new Robin($key, $secret, $url);
            }
        );
    }

    private function bindContracts()
    {
        $this->app->bind(Sender::class, Robin::class);

        $this->app->bind(Retriever::class, SEOShop::class);
    }
}