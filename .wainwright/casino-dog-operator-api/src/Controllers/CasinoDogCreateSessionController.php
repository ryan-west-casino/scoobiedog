<?php
namespace Wainwright\CasinoDogOperatorApi\Controllers;
use Illuminate\Support\Facades\Http;

class CasinoDogCreateSessionController
{
    protected $operator_key;

    public function __construct()
    {
        $this->operator_key = config('casino-dog-operator-api.access.key');
        $this->operator_secret = config('casino-dog-operator-api.access.secret');
        $this->endpoint_create_session = config('casino-dog-operator-api.endpoints.create_session');
        $this->endpoint_available_games = config('casino-dog-operator-api.endpoints.available_games');
    }

    public function test_create()
    {
        $session = $this->create_session('pragmaticexternal:UltraBurn', 'test', 'USD', 'real', true);
        return $session;
    }


    public function create_session(string $game_slug, string $player_id, string $currency, string $mode)
    {
        $build_session_url = $this->endpoint_create_session.'?game='.$game_slug.'&player='.$player_id.'&currency='.$currency.'&operator_key='.$this->operator_key.'&mode='.$mode;
        $http = Http::get($build_session_url);
        return $http;
    }


}
