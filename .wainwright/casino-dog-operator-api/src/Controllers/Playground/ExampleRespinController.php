<?php
namespace Wainwright\CasinoDogOperatorApi\Controllers\Playground;

use Illuminate\Http\Request;
use Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Wainwright\CasinoDogOperatorApi\Models\OperatorGameslist;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Wainwright\CasinoDogOperatorApi\Traits\CasinoDogOperatorTrait;
use Illuminate\Support\Facades\Crypt;

class ExampleRespinController
{
    use CasinoDogOperatorTrait;

    public function check_valid_session($response) {
        try {
        return $response['message']['session_url'];
        } catch(\Exception $e) {
            return "0";
        }
    }


    public function toggle_respin(Request $request)
    {
        if(!$request->pid) {
            abort(403, 'Pid not specified');
        }
        $format_time_to_day = Carbon\Carbon::parse(now())->format('d');
        
        $player_id = md5($request->DogGetIP().$format_time_to_day);
        $lock = Cache::get($player_id.'::toggleRespinCooldown');
        if($lock) {
            $respin_state = (int) $lock;
            if($respin_state === 1) {
                $respin_state = 'enabled';
            } else {
                $respin_state = 'disabled';
            }
            $data = [
                "message" => "Please wait a few more seconds before toggling respin again. Your current respin toggle state is ",
                "pid" => $player_id,
                "respin_state" => $lock,
                "status" => 400,
            ];
            return response()->json($data, 400);
        };
        


        if($player_id === $request->pid) {
           $operator_key = config('casino-dog-operator-api.access.key');
           $toggle_url =  config('casino-dog-operator-api.endpoints.toggle_respin').'?action=toggle_respin&operator_key='.$operator_key.'&operator_player_id='.$player_id;
           $result = Http::get($toggle_url);
        }

        if(!is_int(json_decode($result, true))) {
            $data = [
                "message" => "Unknown error.",
                "error_message" => json_decode($result, true),
                "status" => 500,
            ];
            return response()->json($data, 500);
        }

        $respin_state = (int) json_decode($result, true);
        if($respin_state === 1) {
            $respin_state = 'enabled';
        } else {
            $respin_state = 'disabled';
        }
        

        $data = [
            "pid" => $player_id,
            "message" => "Toggle success. Your current respin state: ".$respin_state,
            "respin_state" => json_decode($result, true),
            "status" => 200,
        ];
        
        Cache::put($player_id.'::toggleRespinCooldown', json_decode($result, true), now()->addSeconds(15));

        return response()->json($data, 200);
    }



    public function view_model($url, $player_id)
    {
        $data = [
            'session_url' => $url,
            'player_id' => $player_id,
        ];
        return view('wainwright::playground.respin-example', compact('data'));
    }

     public function show(Request $request) {
        
        $format_time_to_day = Carbon\Carbon::parse(now())->format('d');
        $player_id = md5($request->DogGetIP().$format_time_to_day);


        // trying to create playson game
        $create_session_request = $this->create_session('infin:LuxorGoldHoldandWin', $player_id, 'USD', 'real');

        if($this->check_valid_session($create_session_request) !== "0") {
            return $this->view_model($this->check_valid_session($create_session_request), $player_id);
        }

        // if mascot game fails, try 3oaks game
        $create_session_request = $this->create_session('3oaks-thunder_of_olympus', $player_id, 'USD', 'real');

        
        if($this->check_valid_session($create_session_request) !== "0") {
            return $this->view_model($this->check_valid_session($create_session_request), $player_id);
        }


        // if playson game fails, try mascot game
        $create_session_request = $this->create_session('mascot:primal_bet_rockways', $player_id, 'USD', 'real');

        if($this->check_valid_session($create_session_request) !== "0") {
            return $this->view_model($this->check_valid_session($create_session_request), $player_id);
        }

        return $this->view_model($this->check_valid_session($create_session_request), $player_id);

        abort(403, 'Error trying to create session, possibly provider code has changed by design else try another provider.');
     }
     
}