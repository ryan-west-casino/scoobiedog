<?php

namespace Wainwright\CasinoDogOperatorApi;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Wainwright\CasinoDogOperatorApi\Commands\InstallCasinoDogOperator;
use Wainwright\CasinoDogOperatorApi\Commands\SyncGameslist;
use Wainwright\CasinoDogOperatorApi\Commands\ConnectApiCommand;
use Wainwright\CasinoDogOperatorApi\Commands\PlayerFundTransferCommand;
use Wainwright\CasinoDogOperatorApi\Middleware\PlaygroundGate;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class CasinoDogOperatorApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('casino-dog-operator-api')
            ->hasConfigFile('casino-dog-operator-api')
            ->hasViews('wainwright')
            ->hasRoutes(['api', 'web'])
            ->hasMigrations(['create_operator_transactions_table', 'create_operator_disabled_games_table', 'create_casino_profiles_table', 'create_playerbalances_table', 'create_operator_gameslist_table'])
            ->hasCommands(ConnectApiCommand::class, PlayerFundTransferCommand::class, InstallCasinoDogOperator::class, SyncGameslist::class);

            //Register the proxy
            $this->app->bind('ProxyHelper', function($app) {
                return new ProxyHelper();
            });

            $this->app->booted(function () {
                $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
                //$kernel->pushMiddleware(\Wainwright\CasinoDogOperatorApi\Middleware\PlaygroundGate::class);
            });

            URL::forceScheme('https');

            Http::macro('dog', function ($dog_method = NULL) {
                if($dog_method !== NULL) {
                    return Http::baseUrl(config('casino-dog-operator-api.endpoints.'.$dog_method));
                } else {
                    return Http::baseUrl(config('casino-dog-operator-api.api_url'));
                }
            });

            Request::macro('DogGetIP', function () {
                foreach (array('HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR') as $key){
                    if (array_key_exists($key, $_SERVER) === true){
                        foreach (explode(',', $_SERVER[$key]) as $ip){
                            $ip = trim($ip); // just to be safe
                            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                                return $ip;
                            }
                        }
                    }
                }
                return request()->ip(); // it will return server ip when no client ip found
            });


    }
}

