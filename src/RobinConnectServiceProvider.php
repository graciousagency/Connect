<?php


namespace Robin\Connect;


use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Robin\Connect\Contracts\Sender;
use Robin\Connect\Contracts\Retriever;
use Robin\Connect\Robin\Api\Client;
use Robin\Connect\SEOShop\Api\Client as SEOShopClient;

class RobinConnectServiceProvider extends ServiceProvider
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

        $this->app->bind(Sender::class, Client::class);

        $this->app->bind(
            Client::class,
            function () {
                $key = env("ROBIN_API_KEY");
                $secret = env("ROBIN_API_SECRET");
                $url = env("ROBIN_API_URL");

                return new Client($key, $secret, $url);
            }
        );

        $this->app->bind(
            Retriever::class,
            SEOShopClient::class
        );
        $this->app->bind(
            SEOShopClient::class,
            function (Application $application) {
                /** @var \WebshopappApiClient $webshopappApiClient */
                $webshopappApiClient = $application->make('WebshopappApiClient');

                return new SEOShopClient($webshopappApiClient);
            }
        );
    }
}