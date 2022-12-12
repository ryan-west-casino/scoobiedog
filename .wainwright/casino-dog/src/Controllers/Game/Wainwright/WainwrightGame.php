<?php
namespace Wainwright\CasinoDog\Controllers\Game\Wainwright;

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
use Wainwright\CasinoDog\Controllers\Game\Bgaming\BgamingGame;

class WainwrightGame extends WainwrightMain
{
    use GameKernelTrait;

    public function game_event($request)
    {
        $bgaming = new BgamingGame;
        return $bgaming->game_event($request);
    }

    public function curl_request($url, $request)
    {
        $response = ProxyHelperFacade::CreateProxy($request)->toUrl($url);
        return $response;
    }
}
