<?php
namespace Wainwright\CasinoDog\Controllers\Game\Playngo;

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

class PlayngoGame extends PlayngoMain
{
    use GameKernelTrait;


    public function game_event(Request $request)
    {
       
        $token = $request->internal_token;
        $game_id = $request->slug;
        $select_session = $this->get_internal_session($token)['data'];

        $api_point = config('casino-dog.games.playngo.new_api_endpoint').$token.'/'.$game_id.'/game_event';
        $full_url = $_SERVER['REQUEST_URI'];

        $original_url = explode('?original=', $full_url);
        $response = $this->curl_request($original_url[1], $request);
	//return $response;
        if(str_contains($response, '!')) {
            $user_id_1 = $this->in_between('"', '!', $response);
            $user_id_2 = $this->in_between('!', '"', $response);
            $this->update_session($token, 'token_original', $user_id_1.'!'.$user_id_2);
        }

        $current_balance = $this->in_between('52 ', ' ', $response);

        if($current_balance) {
            $current_balance = (int) $current_balance;
            $bridge_balance = Cache::get($token.':playngoHiddenBalance');

            if(!$bridge_balance) {
                Cache::set($token.':playngoHiddenBalance', (int) $current_balance);
                $bridge_balance = $current_balance;
            }
            //Log::debug('current_balance: '.$current_balance);
            //Log::debug('bridge_balance: '.$bridge_balance);

            if($bridge_balance !== $current_balance) {
                if($bridge_balance > $current_balance) {
                    $winAmount = 0;
                    $betAmount = $bridge_balance - $current_balance;
                    //Log::debug('debit: '.$betAmount);
                } else {
                    $betAmount = 0;
                    $winAmount = $current_balance - $bridge_balance;
                    //Log::debug('credit: '.$winAmount);
                }
            Cache::set($token.':playngoHiddenBalance', $current_balance);
            $process_and_get_balance = $this->process_game($token, ($betAmount ?? 0), ($winAmount ?? 0), $response);
            $response = str_replace($current_balance, ($process_and_get_balance), $response);
            } else {
                Cache::set($token.':playngoHiddenBalance', $current_balance);
                $get_balance = $this->get_balance($token);
                $response = str_replace($current_balance, ($get_balance), $response);
            }
        }

        $response = str_replace('DEMO', $select_session['currency'], $response);
        $response = str_replace('d=', 'token=123&d=', $response);
        return $response;
    }


    public function proxy_event($url, Request $request) {
        $resp = ProxyHelperFacade::CreateProxy($request)->toUrl($url);
        return $resp;
    }


    public function curl_request($url, $request)
    {

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);

        $headers = array(
        "Accept: */*",
        "Accept-Language: en-ZA,en;q=0.9",
        "Cache-Control: no-cache",
        "Connection: keep-alive",
        "Content-type: text/plain",
        "Origin: https://playngonetwork.com",
        "Pragma: no-cache",
        "Sec-Fetch-Dest: empty",
        "Sec-Fetch-Mode: cors",
        "Sec-Fetch-Site: same-origin",
        "sec-ch-ua-mobile: ?0",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_TIMEOUT, 4000);

        $data = $request->getContent();

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);

        return $resp;
    }

}
