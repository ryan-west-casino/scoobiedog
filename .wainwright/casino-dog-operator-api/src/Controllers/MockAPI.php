<?php
namespace Wainwright\CasinoDogOperatorApi\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Log;
use Wainwright\CasinoDogOperatorApi\Models\PlayerBalances;
use Wainwright\CasinoDogOperatorApi\Traits\CasinoDogOperatorTrait;
use Wainwright\CasinoDogOperatorApi\Models\OperatorGameslist;

class MockAPI
{
    use CasinoDogOperatorTrait;

    public function create_player(Request $request)
    {
        if(!$request->player_id) {
            $error = [
                "status" => "error",
                "reason" => "specify player_id",
            ];
            return response()->json($error, 400);
        } else {
            $player_id = $request->player_id;
        }

        if(!$request->currency) {
            $error = [
                "status" => "error",
                "reason" => "specify currency",
            ];
            return response()->json($error, 400);
        } else {
            $currency = strtoupper($request->currency);
        }

        $player_search = PlayerBalances::where('player_id',  $player_id.'-'.$currency)->first();
        if($player_search) {
            $error = [
                "status" => "error",
                "reason" => "player with this player_id already exists",
            ];
            return response()->json($error, 400);
        }

        $data = [
            'player_id' => $player_id.'-'.$currency,
            'player_name' => 'mock-'.rand(10000000, 99999999999999999),
            'currency' => $currency,
            'balance' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        PlayerBalances::insert($data);
        $player_search = PlayerBalances::where('player_id', $player_id.'-'.$currency)->first();
        $data = [  
            'player_id' => $player_id,
            'secret' => $player_search->player_name,
            'currency' => $player_search->currency,
            'balance' => $player_search->balance,
            ];
            return $data;
        return ;
    }

    public function get_balance(Request $request)
    {
        if(!$request->player_id) {
            $error = [
                "status" => "error",
                "reason" => "specify player_id",
            ];
            return response()->json($error, 400);
        } else {
            $player_id = $request->player_id;
        }

        if(!$request->currency) {
            $error = [
                "status" => "error",
                "reason" => "specify currency",
            ];
            return response()->json($error, 400);
        } else {
            $currency = strtoupper($request->currency);
        }

        if(!$request->secret) {
            $error = [
                "status" => "error",
                "reason" => "specify secret",
            ];
            return response()->json($error, 400);
        } else {
            $secret = $request->secret;
        }

        $player_search = PlayerBalances::where('player_id',  $player_id.'-'.$currency)->where('player_name', $secret)->first();
        if(!$player_search) {
            abort(400, "Player with this player_id & player_name does not exist");
        } else {
            $data = [  
            'player_id' => $player_id,
            'currency' => $player_search->currency,
            'balance' => $player_search->balance,
            ];
            return $data;
        }
    }



    public function set_balance(Request $request)
    {
        if(!$request->player_id) {
            $error = [
                "status" => "error",
                "reason" => "specify player_id",
            ];
            return response()->json($error, 400);
        } else {
            $player_id = $request->player_id;
        }

        if(!$request->currency) {
            $error = [
                "status" => "error",
                "reason" => "specify currency",
            ];
            return response()->json($error, 400);
        } else {
            $currency = strtoupper($request->currency);
        }

        if(!$request->secret) {
            $error = [
                "status" => "error",
                "reason" => "specify secret",
            ];
            return response()->json($error, 400);
        } else {
            $secret = $request->secret;
        }

        $new_balance = (int) $request->new_balance;


        $player_search = PlayerBalances::where('player_id',  $player_id.'-'.$currency)->where('player_name', $secret)->first();
        if(!$player_search) {
            abort(400, "Player with this player_id & player_name does not exist");
        } else {
            $old_balance = $player_search->balance;
            PlayerBalances::where('player_id',  $player_id.'-'.$currency)->update([
                    "balance" => $new_balance
                ]);
            $data = [  
                'player_id' => $player_id,
                'currency' => $player_search->currency,
                'balance' => $new_balance,
                'old_balance' => $old_balance,
                ];
            return $data;
        }
    }

    public function get_game_url(Request $request)
    {
        if(!$request->player_id) {
            $error = [
                "status" => "error",
                "reason" => "specify player_id",
            ];
            return response()->json($error, 400);
        } else {
            $player_id = $request->player_id;
        }

        if(!$request->currency) {
            $error = [
                "status" => "error",
                "reason" => "specify currency",
            ];
            return response()->json($error, 400);
        } else {
            $currency = strtoupper($request->currency);
        }

        if(!$request->game_id) {
            $error = [
                "status" => "error",
                "reason" => "specify game_id, for example: &game_id=mascot:3_corsairs",
            ];
            return response()->json($error, 400);
        } else {
            $game_id = $request->game_id;
        }

        if(!$request->secret) {
            $error = [
                "status" => "error",
                "reason" => "specify secret",
            ];
            return response()->json($error, 400);
        } else {
            $secret = $request->secret;
        }

        $player_search = PlayerBalances::where('player_id',  $player_id.'-'.$currency)->where('player_name', $secret)->first();

        if(!$player_search) {
            $error = [
                "status" => "error",
                "reason" => "could not find player with that id & secret",
            ];
            return response()->json($error, 400);
        } else {
            $game = $this->create_session($game_id, $player_id.'-'.$currency, $currency, 'real');
            $game = json_decode($game, TRUE);
        return response()->json($game, 200);
        }

    }

    public function gameslist(Request $request) {

        if($request->provider) {
            $list = OperatorGameslist::all()->where("provider", $request->provider);
        } else {
            $list = OperatorGameslist::all();
        }
        
        foreach($list as $game) {
                $array[] =  array(
                    "id" => $game['slug'],
                    "name" => $game['name'],
                    "type" => "external",
                    "provider" => $game['provider'],
                    "desc" => "External slots game.",
                    "link" => "/game/external/{$game['slug']}",
                    "class" => [
                        "init" => "\ExternalGame\ExternalGameInit",
                        "meta" => "\ExternalGame\ExternalGameMeta",
                    ],
                    );

        }
            return $array;


    }


}