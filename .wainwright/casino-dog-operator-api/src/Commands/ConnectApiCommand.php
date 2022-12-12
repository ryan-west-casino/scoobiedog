<?php

namespace Wainwright\CasinoDogOperatorApi\Commands;

use Illuminate\Support\Facades\Http;
use Wainwright\CasinoDogOperatorApi\Models\OperatorGameslist;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;
use Symfony\Component\Process\Process;
use Wainwright\CasinoDogOperatorApi\Traits\CasinoDogOperatorTrait;

class ConnectApiCommand extends Command
{
    use CasinoDogOperatorTrait;

    protected $signature = 'casino-dog-operator:connect-to-api';
    public $description = 'Configure your API keys & API endpoints.';

    public function handle()
    {
        $url_get_ip = 'https://api.ipify.org/?format=json';
        //Retrieve & set public IP
        $ip = "error";
        $ip_get = json_decode((Http::timeout(10)->get($url_get_ip)), true);
        if(isset($ip_get['ip'])) {
            $ip = $ip_get['ip'];
        }
        $this->info('Make sure to set proper IP allowed on the main IP. Your IP currently: '.$ip);
        
        if(file_exists(config_path('casino-dog-operator-api.php'))) {
            $config_path = config_path('casino-dog-operator-api.php');
        } elseif(file_exists(base_path('.wainwright/casino-dog-operator-api/config/casino-dog-operator-api.php'))) {
            $config_path = base_path('.wainwright/casino-dog-operator-api/config/casino-dog-operator-api.php');
        } else {
            $this->error('Could not find config/casino-dog-operator-api.php - make sure it is available at .wainwright/casino-dog-operator-api/config or in your base app at /config/.');
        }

        /* API key replacement in config(casino-dog-operator-api.access.key) */
        if($this->confirm('Do you want to update API key?')) {
            $new_apikey = $this->ask('Please enter API key.');
                $currentKey = config('casino-dog-operator-api.access.key');
                $current_key_parse = "'key' => '".$currentKey."'";
                $new_apikey_parse = "'key' => '".$new_apikey."'";
                $this->replaceInFile($current_key_parse, $new_apikey_parse, $config_path);
                $this->info('API key '.$new_apikey.' should be set in config.');
        }

        /* API key replacement in config(casino-dog-operator-api.access.secret) */
        if($this->confirm('Do you want to update API secret?')) {
            $new_secret = $this->ask('Please enter API secret.');
                $current_secret = config('casino-dog-operator-api.access.secret');
                $current_secret_parse = "'secret' => '".$current_secret."'";
                $new_secret_parse = "'secret' => '".$new_secret."'";
                $this->replaceInFile($current_secret_parse, $new_secret_parse, $config_path);
                $this->info('API secret '.$new_secret.' should be set in config.');
        }

        /* API Base URL */
        if($this->confirm('Do you want to update API URL?')) {
            $new_url = $this->ask('Enter API base URL, it should not be ending with slash.', 'https://main-api.777.dog');
                $current_api_url = config('casino-dog-operator-api.api_url');
                $this->replaceInFile($current_api_url, $new_url, $config_path);
                $this->info('API base URL set to '.$new_url.' has been set in config.');
        } 


        /* API Base URL */
        if($this->confirm('Do you want to try and test access connection?')) {
            if(config('casino-dog-operator-api.endpoints.access_ping')) {
                $url = config('casino-dog-operator-api.endpoints.access_ping').'?operator_key='.config('casino-dog-operator-api.access.key');
                $this->info('Sending ping: '.$url);
                try {
                $http = Http::get($url);
                } catch(\Exception $e) {
                    $error = $e->getMessage();
                    $this->error('Connection errored: '.$e);
                    die();
                }
                if($http->status() === 200) {
                    $this->info('Connection seemed to be succesfull.');
                } else {
                    $this->error('Connection seemed to have failed: '.json_encode($http->body()));
                }
                //return $http->getBody();
            };
        } 

        return self::SUCCESS;
    }
}