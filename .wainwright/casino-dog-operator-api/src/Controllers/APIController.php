<?php
namespace Wainwright\CasinoDogOperatorApi\Controllers;

use Illuminate\Http\Request;
use Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Wainwright\CasinoDogOperatorApi\Traits\ApiResponseHelper;
use Wainwright\CasinoDogOperatorApi\Models\OperatorGameslist;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Wainwright\CasinoDogOperatorApi\Traits\CasinoDogOperatorTrait;

class APIController
{
   use ApiResponseHelper, CasinoDogOperatorTrait;

   protected $operator_key;

    public function __construct()
    {
        $this->operator_key = config('casino-dog-operator-api.access.key');
        $this->operator_secret = config('casino-dog-operator-api.access.secret');
    }

   public function gameslist_wainwright($games)
   {
    foreach($games as $game) {
        $explode_game = explode(':', $game->slug);
        if(isset($explode_game[1])) {
            $game_software = $explode_game[0];
            $game_raw = $explode_game[1];
            //$img_url = 'https://win.radio.fm/api/image/'.$game_software.'/'.$game_raw.'.webp';
            $img_url = 'https://wainwrighted.herokuapp.com/https://d1sc13y7hrlskd.cloudfront.net/optimized_images/landscape/'.$game_software.'/'.$game_raw.'.webp';
            if($game->provider === 'netent') {
                $img_url = 'https://wainwrighted.herokuapp.com/https://cdn.softswiss.net/i/s2/'.$game_software.'/'.$game_raw.'.png';
            }
            if($game->provider === 'playngo') {
                $img_url = 'https://wainwrighted.herokuapp.com/https://cdn.softswiss.net/i/s2/'.$game_software.'/'.$game_raw.'.png';
            }

            //$img_url = 'https://cdn.softswiss.net/i/s2/'.$game_software.'/'.$game_raw.'.png';
        } else {
            $img_url = 'https://wainwrighted.herokuapp.com/https://parimatch.co.tz/service-discovery/service/pm-casino/img/tr:n-slots_game_image_desktop/Casino/eva/games/'.$game->gid.'.png';

            //$img_url = 'http://kohengroup.com/thumbnail/i-9102777/'.$game->gid.'.png';
        }

        $tags = array($game->type);

        if($game->bonusbuy === 1) {
            array_push($tags, 'bonusbuy');
        }

        if($game->jackpot === 1) {
            array_push($tags, 'jackpot');
        }

        $games_list[] = array(
            'id' => $game->gid,
            'slug' => $game->slug, //slug used in api
            'name' => $game->name,
            'provider' => $game->provider,
            'tags' => $tags,
            'img' => $img_url, // link to image
            'status' => 'active',
            'created_at' => $game->created_at ?? now(),
            'updated_at' => $game->updated_at ?? now(),
        );
    }
    $games = collect($games_list);
    $games_list = $games->unique();

    return $games_list;
   }


   public function gameslist_management($games)
   {
    foreach($games as $game) {
        $explode_game = explode(':', $game->slug);
        if(isset($explode_game[1])) {
            $game_software = $explode_game[0];
            $game_raw = $explode_game[1];
            $img_url = 'https://wainwrighted.herokuapp.com/https://d1sc13y7hrlskd.cloudfront.net/optimized_images/landscape/'.$game_software.'/'.$game_raw.'.webp';
            if($game->provider === 'netent') {
                $img_url = 'https://wainwrighted.herokuapp.com/https://cdn.softswiss.net/i/s2/'.$game_software.'/'.$game_raw.'.png';
            }
            if($game->provider === 'playngo') {
                $img_url = 'https://wainwrighted.herokuapp.com/https://cdn.softswiss.net/i/s2/'.$game_software.'/'.$game_raw.'.png';
            }
        } else {
            $img_url = 'https://wainwrighted.herokuapp.com/https://parimatch.co.tz/service-discovery/service/pm-casino/img/tr:n-slots_game_image_desktop/Casino/eva/games/'.$game->gid.'.png';
        }

        $tags = array($game->type);

        if($game->bonusbuy === 1) {
            array_push($tags, 'bonusbuy');
        }

        if($game->jackpot === 1) {
            array_push($tags, 'jackpot');
        }
        
        

        $games_list[] = array(
            'id' => $game->id,
            'gid' => $game->gid,
            'slug' => $game->slug, //slug used in api
            'name' => $game->name,
            'provider' => $game->provider,
            'type' => $game->type,
            'popularity' => (int) $game->popularity,
            'bonusbuy' => $game->bonusbuy,
            'jackpot' => $game->bonusbuy,
            'enabled' => $game->enabled,
            'created_at' => $game->created_at ?? now(),
            'updated_at' => $game->updated_at ?? now(),
        );
    }
    $games = collect($games_list);
    $games_list = $games->unique();

    return $games_list;
   }


public function providerslist_wainwright($providers, $count) {
    if($count < 2) {
        $games_count = OperatorGameslist::where('provider', $providers[0]['slug'])->count();
        $providerslist = array(
            'id' => $providers[0]['slug'],
            'slug' => $providers[0]['slug'],
            'name' => ucfirst($providers[0]['name']),
            'parent' => NULL,
            'eligible_games' => $games_count,
            'icon' => 'ResponsiveIcon',
            'provider' => $providers[0]['provider'],
            'created_at' => now(),
            'updated_at' => now(),
        );
    } else {
    foreach($providers as $provider) {
        $games_count = OperatorGameslist::where('provider', $provider['slug'])->count();
        $providerslist[] = array(
            'id' => $provider['slug'],
            'slug' => $provider['slug'],
            'name' => ucfirst($provider['slug']),
            'parent' => NULL,
            'eligible_games' => $games_count,
            'icon' => 'ResponsiveIcon',
            'provider' => $provider['slug'],
            'created_at' => now(),
            'updated_at' => now(),
        );
    }
    }


    return $providerslist;
}

public function game_descriptions() {
    $cache_length = 300; // 300 seconds = 5 minutes

    if($cache_length === 0) {
        $game_desc = file_get_contents(__DIR__.'../../game_descriptions.json');
    }
    $game_desc = Cache::remember('gameDescriptions', 300, function () {
        return file_get_contents(__DIR__.'/../../game_descriptions.json');
    });
    $g2 = json_decode($game_desc, true);

    return $g2;
}

public function tags() {

    $tags = [
    [
        'id' => 1,
        'name' => 'Slots',
        'slug' => 'slots',
        'icon' => null,
        'image' => [],
        'details' => 'Slotmachine Games',
        'type_id' => 3,
        'created_at' => now(),
        'updated_at' => now(),
        'deleted_at' => null,
        'type' => null,
    ],
    [
        'id' => 2,
        'name' => 'Live',
        'slug' => 'live',
        'icon' => null,
        'image' => [],
        'details' => 'Live Games',
        'type_id' => 3,
        'created_at' => now(),
        'updated_at' => now(),
        'deleted_at' => null,
        'type' => null,
    ],
    [
        'id' => 3,
        'name' => 'Jackpot',
        'slug' => 'jackpot',
        'icon' => null,
        'image' => [],
        'details' => 'Jackpot Games',
        'type_id' => 3,
        'created_at' => now(),
        'updated_at' => now(),
        'deleted_at' => null,
        'type' => null,
    ],
    [
        'id' => 4,
        'name' => 'Bonus-Buy',
        'slug' => 'bonusbuy',
        'icon' => null,
        'image' => [],
        'details' => 'Bonus Buy Game Feature',
        'type_id' => 3,
        'created_at' => now(),
        'updated_at' => now(),
        'deleted_at' => null,
        'type' => null,
    ],
    ];

    return collect($tags)->paginate(100);
}

public function play($slug, Request $request)
{
    if($request->ip() === '127.0.0.1') {
        return 'localhost - load /play/localhost/{slug} instead';
    }
    if($request->player) {
        if($request->currency) {
        $player_id = $request->player;
        $player_type = 'account';
        $currency = $request->currency;
        } else {
            return 'Currency not specified';
        }
    } else {
        $ip = $request->ip();
        $format_time_to_hour = Carbon\Carbon::parse(now())->format('H');
        $format_time_to_day = Carbon\Carbon::parse(now())->format('d');
        $player_id = md5($ip.$format_time_to_hour.$format_time_to_day);
        $currency = 'USD';
        $player_type = 'ip-based';
    }
    $mode = 'real';
    $create_session_request = $this->create_session($slug, $player_id, $currency, $mode);

    if(isset($create_session_request['message'])) {
    	if(isset($create_session_request['message']['session_url'])) {
            $data = [
                "id" => $create_session_request['message']['session_url'],
                "name" => $player_id,
                
            ];

	return redirect($create_session_request['message']['session_url']);
	} else {
	return $create_session_request['message'];
	}
    } else {
	return 'error'.$create_session_request;
    }
}

public function games_info($slug, Request $request)
{
    $http = $this->game_info($slug);
    /* Generate a user ID based on IP that resets every hour */
    $ip = $request->ip();
    $format_time_to_hour = Carbon\Carbon::parse(now())->format('H');
    $format_time_to_day = Carbon\Carbon::parse(now())->format('d');
    $player_id = md5($ip.$format_time_to_hour.$format_time_to_day);

    $create_session = env('APP_URL')."/api/play/".$http['slug'];
    $cached_session = Cache::get($player_id.$http['slug']);
    if($cached_session) {
        return $cached_session;
    }

    $orig_tags = $http['tags'];
    $tags = [];
    if(str_contains(json_encode($orig_tags), 'slots')) {
        $data = [
            'id' => 1,
            'name' => 'Slots',
            'slug' => 'slots',
            'icon' => null,
            'image' => [],
            'details' => 'Slotmachine Games',
            'type_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
            'type' => null,
        ];
        array_push($tags, $data);
    }
    if(str_contains(json_encode($orig_tags), 'live')) {
        $data = [
            'id' => 2,
            'name' => 'Live',
            'slug' => 'live',
            'icon' => null,
            'image' => [],
            'details' => 'Live Games',
            'type_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
            'type' => null,
        ];
        array_push($tags, $data);
    }

    if(str_contains(json_encode($orig_tags), 'bonusbuy')) {
        $data = [
            'id' => 3,
            'name' => 'Bonus Buy',
            'slug' => 'bonusbuy',
            'icon' => null,
            'image' => [],
            'details' => 'Bonus Buy Feature',
            'type_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
            'type' => null,
        ];
        array_push($tags, $data);
    }

    if(str_contains(json_encode($orig_tags), 'jackpot')) {
        $data = [
            'id' => 4,
            'name' => 'jackpot',
            'slug' => 'Jackpot',
            'icon' => null,
            'image' => [],
            'details' => 'Jackpot',
            'type_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
            'type' => null,
        ];
        array_push($tags, $data);
    }

    if(str_contains(json_encode($orig_tags), 'casino')) {
        $data = [
            'id' => 4,
            'name' => 'Casino Table Game',
            'slug' => 'casino',
            'icon' => null,
            'image' => [],
            'details' => 'Casino Table Game',
            'type_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
            'type' => null,
        ];
        array_push($tags, $data);
    }

    if(str_contains(json_encode($orig_tags), 'live')) {
        $data = [
            'id' => 1,
            'name' => 'Live',
            'slug' => 'live',
            'icon' => null,
            'image' => [],
            'details' => 'Live Games',
            'type_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
            'type' => null,
        ];
        array_push($tags, $data);
    }
    
    $provider_str = strtolower($http['provider']);
    $fake_iframe_url = $this->fake_iframe_url($provider_str, $http['slug']);

    $data = [
        'id' => $http['id'],
        'name' => $http['name'],
        'slug' => $http['slug'],
        'player_id' => $player_id,
        'description' => $http['description'],
        'real_url' => $create_session,
        'iframe_url' => $fake_iframe_url,
        'status' => 'active',
        'image' => $http['image'],
        'tags' =>  $tags,
        'type' => $http['type'],
        'provider' => [
            'name' => $http['provider'],
            'slug' => $http['provider'],
            'cover_image' => [
                'original' => $http['image'],
                'thumbnail' => $http['image'],
            ],
            'logo' => [
                'original' => $http['image'],
                'thumbnail' => $http['image'],
            ],
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ];
    Cache::set($player_id.$http['slug'], $data, 90);

    return response()->json($data);
}



public function fake_iframe_url($provider, $game_id)
{
    if($provider === 'pragmaticplay') 
    {
        $url = 'https://rarenew-dk4.pragmaticplay.net/gs2c/html5Game.do?jackpotid=0&gname'.$game_id.'&extGame=1&ext=0&cb_target=exist_tab&symbol='.$game_id.'&jurisdictionID=99&minilobby=false&mgckey=AUTHTOKEN@16838e107d0b67d2985b9339344964712bdd9246b134feca3826619ef7d30cf6~stylename@rare_stake~SESSION'.Str::uuid().'tabName=';
    
    } elseif($provider === 'playson')
    {
        $url = 'https://parimatch-dgm.gv-gamespace.com/launch?gameName='.$game_id.'&partner=parimatch-prod&platform=desk&lang=en&token='.Str::uuid();
    } elseif($provider === 'redtiger')
    {
        $url = 'https://alfred.c2.3oaks.com/launcher/parimatch/48/?cid='.Str::uuid().'&productId='.$game_id.'&lang=en&targetChannel=desktop&consumerId=3oaks';
    } elseif($provider === 'nolimitcity')
    {
        $url = 'https://casino.nolimitcdn.com/loader/parimatch.html?cid=parimatch&productId='.$game_id.'&lang=en&targetChannel=desktop&consumerId=nolimitcity&lobbyUrl=https%3A%2F%2Fpari-match.com%2Fen%2Fcasino%2Fslots&providerId=EVA_SLOTS_NOLIMITCITY';
    } elseif($provider === '')
    {
        $url = 'https://exapi.mascot.games/eva/2020-05-22?cid=parimatch&productId=deepsea_riches&lang=en&targetChannel=desktop&consumerId=mascot&lobbyUrl=https%3A%2F%2Fpari-match.com%2Fen%2Fcasino%2Fslots';
    }


    

}

public function game_info($slug)
{
    $select_game = OperatorGameslist::where('slug', $slug)->first();

    if(!$select_game) {
        abort(400, 'Game not found');
    }
    $desc = collect($this->game_descriptions());

    $game_description = $desc->where('identifier', $select_game->gid)->first();
    if(!$game_description) {
        $game_desc = '';
    } else {
        $game_desc = $game_description['description'];
    }

    $tags = array($select_game->type);

    if($select_game->bonusbuy === 1) {
        array_push($tags, 'bonusbuy');
    }

    if($select_game->jackpot === 1) {
        array_push($tags, 'jackpot');
    }

    $explode_game = explode(':', $select_game->slug);
    if(isset($explode_game[1])) {
        $game_software = $explode_game[0];
        $game_raw = $explode_game[1];
        $img_url = 'https://cdn.softswiss.net/i/s2/'.$game_software.'/'.$game_raw.'.png';
    } else {
        $img_url = 'http://kohengroup.com/thumbnail/i-9102777/'.$select_game->gid.'.png';
    }

    $game_info = array(
        'id' => $select_game->gid,
        'slug' => $select_game->slug,
        'name' => $select_game->name,
        'description' => $game_desc,
        'provider' => $select_game->provider,
        'status' => "active",
        'image' => $img_url,
        'tags' => $tags,
        'type' => $select_game->type,
    );

    return $game_info;
}

   public function gameslist_trimmed($games)
   {
    foreach($games as $game) {
        $explode_game = explode(':', $game->slug);
        if(isset($explode_game[1])) {
        $game_software = $explode_game[0];
        $game_raw = $explode_game[1];
        }
        $games_list[] = array(
            'id' => $game->gid,
            'api' => $game->slug, //slug used in api
            'n' => $game->name, // game name
            'bb' => $game->bonusbuy, // bonusbuy
            'jp' => $game->jackpot,
            'p' => $game->provider, // game provider
            'dp' => $game->demoplay, //demoplay
            't' => $game->type, //game type
            'img' => 'https://cdn.softswiss.net/i/s3/'.$game_software.'/'.$game_raw.'.png', // link to image
            'pop' => $game->popularity, // popularity game (lower is higher place on list)
            'on' => $game->enabled, //if game is enabled
        );
    }

    $schema_layout = array(
            'id' => 'game_id',
            'api' => 'game_slug',
            'n' => 'game_name',
            'p' => 'game_provider',
            't' => 'game_type',
            'img' => 'game_image',
            'pop' => 'game_popularity',
            'bb' => 'game_feature_bonusbuy',
            'jp' => 'game_feature_jackpot',
            'dp' => 'game_feature_demoplay',
            'on' => 'game_enabled',
    );

    return $games_list;
   }

   public function games(Request $request)
   {
	return $this->gamesListEndpoint('wainwright_casino', $request);
   }

   public function games_all(Request $request)
   {
	return $this->gamesListEndpoint('management', $request);
   }

   public function gamesListEndpoint(string $layout, Request $request)
   {
         $start = microtime(true);

        $cache_length = 60;
        $limit = 20;
        if($request->limit) {
            if(is_numeric($request->limit)) {
                if($request->limit > 0) {
                    if($request->limit > 100) {
                        $limit = (int) 100;
                    } else {
                    $limit = (int) $request->limit;
                    }
                }
            }
        }

        if($cache_length < 5) {
            $games = OperatorGameslist::get()->sortBy('popularity');
        } else {
        $games = Cache::remember('getGamesList', $cache_length, function () {
            return OperatorGameslist::all()->sortBy('popularity');
        });
        }

       $bonus = 0;
       $jackpot = 0;

       if($request->searchJoin) {
           if(str_contains('tags.slug:bonusbuy', $request->searchJoin)) {
              $games = $games->where('bonusbuy', '=', 1)->all();
           }
           if(str_contains('tags.slug:jackpot', $request->searchJoin)) {
              $games = $games->where('jackpot', '=', 1)->all();
           }
        }


       if($request->bonus) {
          if($request->bonus === '1') {
              $games = $games->where('bonusbuy', '=', 1)->all();
          }
       }
        if($request->jackpot) {
           if($request->jackpot === '1') {
               $games = $games->where('jackpot', '=', 1)->all();
           }
        }


        if($request->search) {
          $provider_explode = explode('categories.slug:', $request->search);
          if(isset($provider_explode[1])) {
              $exploded = explode(';', $provider_explode[1]);
              $games = $games->where('provider', $exploded[0])->all();
          }
        }

        if($request->provider) {
            $games = $games->where('provider', $request->provider)->all();
        }

        $showAll = 0;
        if($request->showAll) {
            if($request->showAll === 'true') {
            $showAll = 1;
            }
        }

        if($layout === 'ocb') {
            $games = $this->gameslist_ocb($games);
        }

        if($layout === 'trimmed') {
            $games = $this->gameslist_trimmed($games);
        }

        if($layout === 'wainwright_casino') {
            $games = $this->gameslist_wainwright($games);
            $result = collect($games)->paginate($limit);
            return $result;
        }

        if($layout === 'management') {
            $limit = 10000;
            $games = $this->gameslist_management($games);
        }


        if($layout === 'all') {
            $limit = 10000;
            $games = $this->gameslist_wainwright($games);
        }

        // Execute the query
        $time = microtime(true) - $start;
        $time = array('query_time' => $time);
        $result = collect($games);

        $min_popularity = $result->min('popularity');
        $max_popularity = $result->max('popularity');
        $median_popularity = $result->median('popularity');

        $paginated_result = $result->paginate($limit);
        $result = array('collection_extra' => array('averages' => array('field_popularity' => array('min' => $min_popularity, 'median' => $median_popularity, 'max' => $max_popularity))), 'server_data' => $time, $result);
        return $result;
    }


   public function categories(Request $request)
   {
	return $this->providersListEndpoint($request);
   }

   public function providersListEndpoint(Request $request)
   {
    $cache_length = 60;
    $limit = 25;
    if($request->limit) {
        if(is_numeric($request->limit)) {
            if($request->limit > 0) {
                if($request->limit > 100) {
                    $limit = (int) 100;
                } else {
                $limit = (int) $request->limit;
                }
            }
        }
    }

    $providers = collect(OperatorGameslist::providers());
    return collect($this->providerslist_wainwright($providers, $limit))->paginate($limit);

   }



   public function createSessionEndpoint(Request $request)
    {
        $validate = $this->createSessionValidation($request);
        if($validate->status() !== 200) {
            return $validate;
        }
        $data = [
            'game' => $request->game,
            'currency' => $request->currency,
            'player' => $request->player,
            'operator_key' => $request->operator_key,
            'mode' => $request->mode,
            'request_ip' => CasinoDog::requestIP($request),
        ];

        $session_create = SessionsHandler::createSession($data);
        if($session_create['status'] === 'success') {
            
            return response()->json($session_create, 200);
        } else {
            return $this->respondError($session_create);
        }
    }

    public function createSessionValidation(Request $request) {
        $validator = Validator::make($request->all(), [
            'game' => ['required', 'max:65', 'min:3'],
            'player' => ['required', 'min:3', 'max:100', 'regex:/^[^(\|\]`!%^&=};:?><’)]*$/'],
            'currency' => ['required', 'min:2', 'max:7'],
            'operator_key' => ['required', 'min:10', 'max:50'],
            'mode' => ['required', 'min:2', 'max:15'],
        ]);

        if ($validator->stopOnFirstFailure()->fails()) {
            $errorReason = $validator->errors()->first();
            $prepareResponse = array('message' => $errorReason, 'request_ip' => CasinoDog::requestIP($request));
            return $this->respondError($prepareResponse);
        }

        $operator_verify = OperatorsController::verifyKey($request->operator_key, CasinoDog::requestIP($request));
        if($operator_verify === false) {
                $prepareResponse = array('message' => 'Operator key did not pass validation.', 'request_ip' => CasinoDog::requestIP($request));
                return $this->respondError($prepareResponse);
        }

        $operator_ping = OperatorsController::operatorPing($request->operator_key, CasinoDog::requestIP($request));
        if($operator_ping === false) {
            $prepareResponse = array('message' => 'Operator ping failed on callback.', 'request_ip' => CasinoDog::requestIP($request));
            return $this->respondError($prepareResponse);
        }

        if($request->mode !== 'real') {
            $prepareResponse = array('message' => 'Mode can only be \'demo\' or \'real\'.', 'request_ip' => CasinoDog::requestIP($request));
            return $this->respondError($prepareResponse);
        }
        return $this->respondOk();
    }
}
