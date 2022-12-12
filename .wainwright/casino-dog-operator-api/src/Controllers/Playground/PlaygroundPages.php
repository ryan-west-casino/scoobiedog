<?php
namespace Wainwright\CasinoDogOperatorApi\Controllers\Playground;

use Illuminate\Http\Request;
use Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Wainwright\CasinoDogOperatorApi\Models\OperatorGameslist;
use Illuminate\Support\Str;
use Wainwright\CasinoDogOperatorApi\Traits\CasinoDogOperatorTrait;
use Illuminate\Support\Facades\Route;

class PlaygroundPages
{
    use CasinoDogOperatorTrait;
    public function view_gameslists(Request $request) {
        $paginate_limit = 50;
        $count = OperatorGameslist::count();
        if($count < 50) {
            $paginate_limit = $count;
        }
        $games = OperatorGameslist::latest()->paginate($paginate_limit);
        if($request->filter) {
            if($request->filter === 'provider') {
                if($request->filter_value) {
                    $count =  OperatorGameslist::where('provider', $request->filter_value)->count();
                    if($count < 50) {
                        $paginate_limit = $count;
                    }
                    $providers = collect(OperatorGameslist::providers());
                    $select_provider = $providers->where('slug', $request->filter_value)->first();
                    if(!$select_provider) {
                        abort(403, "No games found.");
                    } else {
                    $games = OperatorGameslist::where('provider', $request->filter_value)->paginate($paginate_limit);
                    }
                }
            }
        } 
        $games_list = [
            'games' => $games,
            'providers' => OperatorGameslist::providers(),
        ];
        return view('wainwright::playground.gameslist', compact('games_list'));
     }

     public function view_index(Request $request) {
        $allRoutes = Route::getRoutes()->getRoutes(); // fetch all rotues as array
        $name = 'playground'; // specify your full route name

        $grouped_routes = array_filter($allRoutes, function($route) use ($name) {
                $action = $route->getAction(); // getting route action
                if (isset($action['as'])) {
                    if (is_array($action['as'])) {
                        return in_array($name, $action['as']);
                    } else {
                        return $action['as'] == $name;
                    }
                }
                return false;
            });
            // output of route objects in the 'main' group
            $data = [
                'routes' => $grouped_routes,
            ];
            return view('wainwright::playground.index', compact('data'));

     }

     public function view_gameframe(Request $request) {
        if(!$request->game_id) {
            return redirect()->back();
        }

        $format_time_to_hour = Carbon\Carbon::parse(now())->format('H');
        $format_time_to_day = Carbon\Carbon::parse(now())->format('d');
        $player_id = md5($request->DogGetIP().$format_time_to_hour.$format_time_to_day.now());
        $create_session_request = $this->create_session($request->game_id, $player_id, 'USD', 'real');
        if(!isset($create_session_request['message'])) {
            abort(401, $create_session_request);
        }
        $data = [
            'session_url' => $create_session_request['message']['session_url'],
            'player_id' => $player_id,
        ];
        
        return view('wainwright::playground.viewer', compact('data'));
     }

     public function redirect_fresh(Request $request)
     {
        $get_game_count = \Wainwright\CasinoDog\Models\GameTemplateQueued::where('completed', false)->count();
        if($get_game_count < 20) {
            $eligible_count = $get_game_count;
        } else {
            $eligible_count = 20;
        }
        $get_game = \Wainwright\CasinoDog\Models\GameTemplateQueued::where('completed', false)->take($eligible_count)->get();
        $game_id = $get_game->random()['slug'];

        $count_current = \Wainwright\CasinoDog\Models\GameTemplate::where('gid', $game_id)->count();
        if($count_current > 10000) {
            $get_game = \Wainwright\CasinoDog\Models\GameTemplateQueued::where('slug', $game_id)->update([
                'completed' =>true
            ]);
        }

        $format_time_to_hour = Carbon\Carbon::parse(now())->format('H');
        $format_time_to_day = Carbon\Carbon::parse(now())->format('d');
        $player_id = md5($request->DogGetIP().$format_time_to_hour.$format_time_to_day.now());
        $create_session_request = $this->create_session($game_id, $player_id, 'USD', 'real');
        if(!isset($create_session_request['message'])) {
            abort(401, $create_session_request);
        }
        $url = $create_session_request['message']['session_url'];
        
        
        return redirect($url);

     }
     
}
