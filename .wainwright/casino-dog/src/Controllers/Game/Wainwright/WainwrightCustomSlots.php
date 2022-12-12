<?php
namespace Wainwright\CasinoDog\Controllers\Game\Wainwright;

use Wainwright\CasinoDog\Controllers\Game\GameKernel;
use Wainwright\CasinoDog\Controllers\Game\GameKernelTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DB;
class WainwrightCustomSlots extends WainwrightMain
{


    /*
    * Custom slots meaning that we will be using the backend of a different provider, in this case Bgaming and previewing own frontend to show example of how to create own slots using the RNG/backend of other providers
    */

    use GameKernelTrait;

    public function gameslist() 
    {   
        $games = [
            "games" =>
                [
                    'gid' => "wainwright/TolarsOcean",
                    "gid_extra" => "softswiss/FruitsMillion",
                    "slug" => "wainwright:tolarsocean",
                    "name" => "Tolar\'s Ocean",
                    "type" => "slots",
                    "typeRating" => 100,
                    "provider" => "wainwright",
                    "popularity" => 100,
                    "jackpot" => 0,
                    "bonusbuy" => 0,
                    "demoplay" => 1,
                    "source" => "internal_custom_slot",
                    "source_schema" => "softswiss",
                    "enabled" => 1,
                    "method" => "bridge_bgaming",
                    "game_function" => "tolarsocean",
                    "game_provider_backend" => "bgaming",
                ],
                [
                    'gid' => "wainwright/HappyBillions",
                    "gid_extra" => "softswiss/FruitsMillion",
                    "slug" => "wainwright:happybillions",
                    "name" => "Happy Billions",
                    "type" => "slots",
                    "typeRating" => 100,
                    "provider" => "wainwright",
                    "popularity" => 100,
                    "jackpot" => 0,
                    "bonusbuy" => 0,
                    "demoplay" => 1,
                    "source" => "internal_custom_slot",
                    "source_schema" => "softswiss",
                    "enabled" => 1,
                    "method" => "bridge_bgaming",
                    "game_function" => "happybillions",
                    "game_provider_backend" => "bgaming",
                ],
            ];
        return $games;
    }
        


    public function tolarsocean($internal_token)
    {

        $skin = 'basic';
        $game = 'FruitMillion';

        $url = Http::get('https://bgaming-network.com/play/'.$game.'/FUN?server=demo');

        $select_session = $this->get_internal_session($internal_token);
        $new_api_endpoint = config('casino-dog.games.wainwright.new_api_endpoint').$internal_token.'/';  // building up the api endpoint we want to receive game events upon

        $gc = $url;
        $gc = str_replace('https://bgaming-network-mga.com/api/', $new_api_endpoint, $gc);  // swap the legitimate game endpoint to ours
        $gc = str_replace('https://bgaming-network.com/api/', $new_api_endpoint, $gc);  // swap the legitimate game endpoint to ours
        $gc = str_replace('googletagmanager.com', 'bogged.', $gc); // remove googletagmanager.com
        $gc = str_replace('FUN', $select_session['data']['currency'], $gc); // change curerncy
        $gc = str_replace('https://boost.bgaming-network.com/analytics.js', 'dynamic_asset/bgaming/analytics.js?game='.$select_session['data']['game_id'], $gc);
        $gc = str_replace('cdn.bgaming-network.com', 'wainwrighted.herokuapp.com/https://static-1.bragg.app', $gc); 

        $gc = str_replace('<title>Fruit Million</title>', '<title>Tolar\'s Ocean</title>', $gc);
        //$url = str_replace('https://rules.bgaming-network.com/en/BonanzaBillion.json', 'https://play.frame.bet/lang/fm/fm_en.json', $url);
        $gc = str_replace('016810e135', ' ', $gc);
        $gc = str_replace('FUN', 'USD', $gc);
        $gc = str_replace('ff2c2c', 'ffffff', $gc);
	$gc = str_replace('fonts.gstatic.com', 'wainwrighted.herokuapp.com/https://fonts.gstatic.com', $gc);
        $gc = str_replace($game, 'TolarsOcean', $gc);
	$gc = str_replace('static.cloudflareinsights.com', 'wainwrighted.herokuapp.com/https://static.cloudflareinsights.com', $gc);
        $gc = str_replace('resources_path":"https://cdn.bgaming-network.com/html/FruitMillion', 'resources_path":"https://static-1.bragg.app/custom_slot/TolarsOcean/', $gc);
        $gc = str_replace('/html/', '/custom_slot/', $gc);
        $gc = str_replace('https://cdn.bgaming-network.com/html/FruitMillion/bundle.js', 'https://static-1.bragg.app/custom_slot/TolarsOcean/basic/v0.0.1/bundle.js', $gc);
        $gc = str_replace('"websocket_url":"wss://bgaming-network.com:', '"websocket_url":"wss://cherry.games:', $gc);
        $gc = str_replace('https://boost.bgaming-network.com/analytics.js', ' ', $gc);
        $gc = str_replace(':96.0', ':85.0', $gc);
        $gc = str_replace('https://js-agent.newrelic.com/nr-1215.min.js', ' ', $gc);
        ///$url = str_replace('https://translations.bgaming-network.com/FruitMillion/en.json', 'https://play.frame.bet/lang/fm_en.json', $url);
        $gc = str_replace('"skin":"patrick"', '"skin":"basic"', $gc);
        $gc = str_replace('"skin":"xmass"', '"skin":"basic"', $gc);
        $gc = str_replace('"skin":"patrick"', '"skin":"basic"', $gc);
        $gc = str_replace('"skin":"valentine"', '"skin":"basic"', $gc);
        $gc = str_replace('"skin":"summer"', '"skin":"basic"', $gc);
        $gc = str_replace('"skin":"easter"', '"skin":"basic"', $gc);
        $gc = str_replace('"skin":"halloween"', '"skin":"basic"', $gc);
        $gc = str_replace('"skin":"oktoberfest"', '"skin":"basic"', $gc);

        return $gc; // return the edited HTML to WainwrightSessions
    }


}
