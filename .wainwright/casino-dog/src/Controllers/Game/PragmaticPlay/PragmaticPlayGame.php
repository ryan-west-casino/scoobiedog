<?php
namespace Wainwright\CasinoDog\Controllers\Game\PragmaticPlay;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Wainwright\CasinoDog\Facades\ProxyHelperFacade;
use Wainwright\CasinoDog\Controllers\Game\GameKernelTrait;
use Illuminate\Http\Client\ConnectionException;
use Wainwright\CasinoDog\Controllers\Game\GameKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Wainwright\CasinoDog\Controllers\Game\OperatorsController;
class PragmaticPlayGame extends PragmaticPlayMain
{
    use GameKernelTrait;

    public function game_event(Request $request)
    {
        $internal_token = $request->internal_token;
        $action = $request->action;
        $parent_session = $this->get_internal_session($internal_token);

        if($action === 'reloadBalance.do') {
            return $this->reloadBalance($internal_token, $request);

        } elseif($action === 'doInit') {
            $freespin_state = $this->check_freespin_state($internal_token);
            if(Cache::get($parent_session['data']['player_operator_id'].'::freespins_'.$parent_session['data']['game_id'])) {
                $freespin_state = Cache::get($parent_session['data']['player_operator_id'].'::freespins_'.$parent_session['data']['game_id']);
            }
            if($freespin_state !== NULL) {
                Cache::set($parent_session['data']['player_operator_id'].'::freespins_added', $freespin_state);
                return 'fra='.$freespin_state['data']['total_win'].'&nas=13&cfgs=4883&ver=2&frn='.$freespin_state['data']['total_spins'].'&index=1&frt=N&ev=FR0~0.01,20,10,0,1,'.$freespin_state['data']['expiration_stamp'].',1,,&mb=0,0,0&rt=d&'.$this->doInit($internal_token, $request);
            }
            return $this->doInit($internal_token, $request);

        } elseif($action === 'doSpin' || $action === 'doCollect' || $action === 'doWin' || $action === 'doDeal') {
            if(Cache::get($parent_session['data']['player_operator_id'].'::freespins_'.$parent_session['data']['game_id'])) {

                return $this->doSpin($internal_token, $request, "doFreeSpin");
            }
            return $this->doSpin($internal_token, $request, $action);
        } elseif($action === 'saveSettings.do') {
            return $this->curl_request($request);
        } else {
            return $this->curl_request($request);
        }

    }


    public function promo_event(Request $request)
    {
        $action = $request->action;
         return '{"error":0,"description":"OK","announcements":[]}';
   }

    public function reloadBalance($internal_token, Request $request) {
        $send_event = $this->curl_request($request);
        $query = $this->parse_query($send_event);
        $get_balance = $this->get_balance($internal_token) / 100;
        $query['balance'] = $get_balance;
        $query['balance_cash'] = $get_balance;
        $query = $this->build_response_query($query);
        return $query;
    }

    public function doInit($internal_token, Request $request) {
        $bridged_session_token = $this->create_new_bridge_session($internal_token, $request);

        $send_event = $this->curl_request($request);
        $query = $this->parse_query($send_event);
        $get_balance = $this->get_balance($internal_token) / 100;
        $query['balance'] = $get_balance;
        $query['balance_cash'] = $get_balance;
        $query['rtp'] = '1.00';
        //$query['gameInfo'] = '{props:{max_rnd_sim:"19230769",max_rnd_hr:"1",max_rnd_win:"200"}}';
        $query['cfgs'] = '2523';

        $bridge_init = str_replace('mgckey', 'mgckey='.$bridged_session_token.'&old_mgckey=', $request->getContent());
        $bridge_send = $this->curl_cloned_request($internal_token, $bridge_init, $request);
        $query = $this->build_response_query($query);


        return $query;
    }



    public function create_new_bridge_session($internal_token, Request $request) {
        $bridge_session = new PragmaticPlaySessions();
        $session = $bridge_session->fresh_game_session($request->symbol, 'token_only');
        $update_session = $this->update_session($internal_token, 'token_original_bridge', $session);
        Cache::put($session.':index', 2, now()->addHours(6));
        Cache::put($session.':counter', 3, now()->addHours(6));
        Cache::put($session.':balance', 10000000);

        return $update_session['data']['token_original_bridge'];
    }

    public function getAmount($money)
    {
        $cleanString = preg_replace('/([^0-9\.,])/i', '', $money);
        $onlyNumbersString = preg_replace('/([^0-9])/i', '', $money);
        $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;
        $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
        $removedThousandSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '',  $stringWithCommaOrDot) * 100;
        return (float) str_replace(',', '.', $removedThousandSeparator);
    }

    public function doSpin($internal_token, Request $request, $action = NULL) {
        $rand_internal_id = rand(0, 100000);
        $parent_session = $this->get_internal_session($internal_token);
        $token_original_bridge = $parent_session['data']['token_original_bridge'];
        $altered_win_request = $request->toArray();

        if(isset($altered_win_request['mgckey'])) {
            $altered_win_request['mgckey'] = $token_original_bridge;
        }
        if(isset($altered_win_request['index'])) {
            $altered_win_request['index'] = Cache::get($token_original_bridge.':index');
            $altered_win_request['counter'] = Cache::get($token_original_bridge.':counter');
        }

        $cloned_request = (clone $request)->replace($altered_win_request); // build a new request with existing original headers from player, we are only replacing body content
        $respin_send_event = $this->curl_request($cloned_request);
        $query = $this->parse_query($respin_send_event);

        $new_bridge_balance = $this->getAmount($query['balance']);
        $old_bridge_balance_cache = Cache::get($token_original_bridge.':balance');
        $old_bridge_index_cache = Cache::get($token_original_bridge.':index');
        $old_bridge_counter_cache = Cache::get($token_original_bridge.':counter');

        $difference = (int) $new_bridge_balance - $old_bridge_balance_cache;

        if($difference < 0) {
            $bet_amount = str_replace('-', '', $difference);
            $freespin_state = Cache::get($parent_session['data']['player_operator_id'].'::freespins_'.$parent_session['data']['game_id']);
            if(!$freespin_state) {
                $process_game = $this->process_game($internal_token, $bet_amount, 0, $query);
            } else {
                $process_game = $this->process_game($internal_token, 0, 0, $query);
            }
        } else {
            $win_amount = $difference;
            if($action === "doFreeSpin") {
                $freespin_state['data']['total_win'] = $win_amount;
            }
            $process_game = $this->process_game($internal_token, 0, $win_amount, $query);
        }

        //Log::debug('callback: '.(int) $process_game);

        $query['balance'] = $process_game / 100;
        $query['balance_cash'] = $process_game / 100;

        Cache::forget($token_original_bridge.':balance');
        Cache::put($token_original_bridge.':balance', $new_bridge_balance);
        Cache::forget($token_original_bridge.':index');
        Cache::put($token_original_bridge.':index', $old_bridge_index_cache + 1);
        Cache::forget($token_original_bridge.':counter');
        Cache::put($token_original_bridge.':counter', $old_bridge_counter_cache + 2);
        $freespin_state = Cache::get($parent_session['data']['player_operator_id'].'::freespins_'.$parent_session['data']['game_id']);
        if($freespin_state) {
            if($freespin_state['data']['total_spins'] < 1) {
                Cache::forget($parent_session['data']['player_operator_id'].'::freespins_'.$parent_session['data']['game_id']);
                $total_win = $freespin_state['data']['total_win'];
                $this->freespin_state_completed($freespin_state);
                return 'ev=FR1~0.00,20,'.$total_win.',,&fra='.$total_win.'&nas=13&cfgs=4883&ver=2&frn=0&frt=N&'.$this->build_response_query($query);
            }
            $freespin_state['data']['total_spins'] = ((int) $freespin_state['data']['total_spins'] - 1);
            Cache::set($parent_session['data']['player_operator_id'].'::freespins_added', $freespin_state);

            return 'fra='.$freespin_state['data']['total_win'].'&nas=13&cfgs=4883&ver=2&frn='.$freespin_state['data']['total_spins'].'&frt=N&'.$this->build_response_query($query);
        }
        return $this->build_response_query($query);
    }

    public function old_game_mechanic()
    {
        $balance_call_needed = true;
        $bonus_active = false;

        if(isset($query['fs_total'])) { //payout bonus game
            $bonus_active = true;
            $win_amount = $query['tw'];
            $process_game = $this->process_game($internal_token, 0, $win_amount, $query);
            $query['balance'] = $process_game;
            $query['balance_cash'] = $process_game;
            return $this->build_response_query($query);
        }

        if(isset($query['fs'])) {
            $bonus_active = true;
            $fs = $query['fs'];

        if(isset($query['fs_bought'])) {
                if($fs === 1) {
                    $bet_amount = $query['c'] * $query['l'] * 100; // credit * lines * 100 (convert to 100 coin value)
                    $process_game = $this->process_game($internal_token, ($bet_amount * 100), 0, $query);
                    if(is_numeric($process_game)) {
                        $balance = $process_game / 100;
                        $query['balance'] = $balance;
                        $query['balance_cash'] = $balance;
                        return $this->build_response_query($query);
                    } else
                    { //throw insufficient balance error
                        if($process_game === '-1') {
                            return '-1&balance=-1&balance_cash=-1';
                        } else {
                            Log::notice('Unknown bet processing error occured: '.$request);
                            return 'unlogged'; // returning this will log out the session
                        }
                    }
                }
            }
        }

        if(isset($query['c'])) { // check if it's bet call
            if($query['na'] === 's') {
                $bet_amount = $query['c'] * $query['l'] * 100; // credit * lines * 100 (convert to 100 coin value)
                if($bonus_active === true) {
                    $bet_amount = 0;
                }
                $process_game = $this->process_game($internal_token, $bet_amount, 0, $query);
                $balance_call_needed = false;
                if(is_numeric($process_game)) {
                    $balance = $process_game / 100;
                } else
                { //throw insufficient balance error
                    if($process_game === '-1') {
                        return '-1&balance=-1&balance_cash=-1';
                    } else {
                        Log::notice('Unknown bet processing error occured: '.$request);
                        return 'unlogged'; // returning this will log out the session
                    }
                }
            }
        }

        if(isset($query['w'])) {
            $selectWinArgument = $query['w'];
            $winRaw = floatval($selectWinArgument);
            if($winRaw !== '0.00') {
                $win_amount = $query['w'] * 100;
                if($bonus_active === true) {
                    $win_amount = 0;
                }
                $process_game = $this->process_game($internal_token, 0, $win_amount, $query);
                $balance = $process_game / 100;
                $balance_call_needed = false;
            }
        }

        if($balance_call_needed === true) {
            $balance = $this->get_balance($internal_token) / 100;
        }

        $query['balance'] = $balance;
        $query['balance_cash'] = $balance;
        $query = $this->build_response_query($query);

        return $query;
    }


    public function build_response_query($query)
    {
        $resp = http_build_query($query);
        $resp = urldecode($resp);
        return $resp;
    }

    public function parse_query($query_string)
    {
        parse_str($query_string, $q_arr);
        return $q_arr;
    }

    public static function proxy_event($internal_token, $request) {
        $resp = ProxyHelperFacade::CreateProxy($request)->toHost('https://demogamesfree.pragmaticplay.net', 'api/games/pragmaticplay/'.$internal_token);
        return $resp;
    }

    public function curl_cloned_request($internal_token, $data, $request)
    {
        $internal_token = $request->segment(4);
        $url_explode = explode($internal_token, $request->fullUrl());
        $url = 'https://demogamesfree.pragmaticplay.net'.$url_explode[1];

        $response = Http::retry(1, 1500, function ($exception, $request) {
            return $exception instanceof ConnectionException;
        })->withBody(
            $data, 'application/x-www-form-urlencoded'
        )->post($url);

        return $response;
    }

    public function curl_request(Request $request)
    {
        $internal_token = $request->segment(4);
        $url_explode = explode($internal_token, $request->fullUrl());
        $url = 'https://demogamesfree.pragmaticplay.net'.$url_explode[1];
        $data = $request->getContent();

        $response = Http::retry(1, 1500, function ($exception, $request) {
            return $exception instanceof ConnectionException;
        })->withBody(
            $data, 'application/x-www-form-urlencoded'
        )->post($url);

        return $response;
    }
    }