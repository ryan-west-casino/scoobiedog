<?php
namespace Wainwright\CasinoDogOperatorApi\Controllers;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use DB;
use Wainwright\CasinoDogOperatorApi\CasinoDogOperatorApi;
use Ably;
use Spatie\Browsershot\Browsershot;

class TestingController
{
    public function __construct() {

        if(env('APP_ENV') !== 'local') {
            abort(400, 'Only available in APP_ENV=local');
        }
    }
    public function handle($function = NULL, Request $request) {
        if($function === NULL) {
            return 'Specify function.';
        }
        return $this->$function($request);
    }

    protected function replaceInFile($search, $replace, $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }

    public function test_view(Request $request) {
        return view('wainwright::iframe-view')->with('url', 'https://127.0.0.1/testing/redirectping');
    }
    
    public function test_ably_view(Request $request) {
        $data = [
            'API_KEY' => config('ably.key'),
            'CHANNEL' => 'testChannel',
        ];
        return view('wainwright::ably-listen-view')->with('data', $data);
    }

    public function test_browser_shot(Request $request) {
return $request->getDogIP();
    }    

    public function getIp(){
        foreach (array('HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); // it will return server ip when no client ip found
    }

    public function test_redirect_ping(Request $request) {
        if($request->ping_param) {

        }
        echo Ably::time(); // 1467884220000
        echo '<a href="/testing/iframe_redirect_view">back to iframe_redirect_view</a>';
        $token = Ably::auth()->requestToken(['clientId' => $request->ip()]); // Ably\Models\TokenDetails
        Ably::channel('testChannel')->publish('test_redirect_ping', 'Someone hit the redirect iframe method view:  '. $request->path().' from '.$this->getIp(), $request->ip());
    }

    public function test_serverip(Request $request)
    {
        $ip_server = $_SERVER['SERVER_ADDR'];
        // Printing the stored address
        echo "Server IP Address is: $ip_server";
    }

    public function dog_test(Request $request) 
    {
        return Http::dog('gameslist')->get('/');
    }



// function to parse the http auth header
function http_digest_parse($txt)
{
    // protect against missing data
    $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
    $data = array();
    $keys = implode('|', array_keys($needed_parts));

    preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

    foreach ($matches as $m) {
        $data[$m[1]] = $m[3] ? $m[3] : $m[4];
        unset($needed_parts[$m[1]]);
    }

    return $needed_parts ? false : $data;
}
    public function testauth()
    {
        $realm = 'Restrifcted area';

//user => password
$users = array('admi2n' => 'mypass', 'guest' => 'guest');
header('HTTP/1.1 401 Unauthorized');


if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Digest realm="'.$realm.
           '",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');

    die('Text to send if user hits Cancel button');
}


// analyze the PHP_AUTH_DIGEST variable
if (!($data = $this->http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) ||
    !isset($users[$data['username']]))
    die('Wrong Credentials!');


// generate the valid response
$A1 = md5($data['username'] . ':' . $realm . ':' . $users[$data['username']]);
$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
$valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);

if ($data['response'] != $valid_response)
    die('Wrong Credentials!');

// ok, valid username & password
echo 'You are logged in as: ' . $data['username'];

    }

    public function iframe_redirect_view(Request $request) 
    {
        echo 'Pinged ably'; // 1467884220000
        $token = Ably::auth()->requestToken(['clientId' => $request->ip()]); // Ably\Models\TokenDetails
        Ably::channel('testChannel')->publish('test_redirect_ping', '['.now().'] - redirect iframe method view hit:  '. $request->path().' from '.$this->getIp(), $request->ip());
        $url_to_catch = 'http://127.0.0.1:5000/testing/test_redirect_ping';

        return view('wainwright::redirect-test');
    }


    public function test_urlscan_start_job(Request $request)
    { // set urlscan.io api-key in config/casino-dog.php
        if(!$request->url) {
            $url = 'https://bgaming-network.com/play/ZorroWildHeart/FUN?server=demo';
            echo 'did not specify ?url so set'.$url;
        } else {
            $url = $request->url;
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return "Does not seem a valid URL";
        }

        $init = new \Wainwright\CasinoDog\Controllers\UrlscanController;
        $data = $init->urlscan_start_job($url);
        return $data;
    }

    public function list_methods(Request $request) {
        try {
            $push_message = '';
            if(!$request->controller) {
                $push_message = 'You did not specify controller, printing TestingController. Define controller in URL params "?controller=[CONTROLLER_CLASSNAME]".';
                $controller = "TestingController";
            } else {
                $controller = $request->controller;
            }
            $namespace = "Wainwright\CasinoDogOperatorApi\Controllers";
            $controller_class = new \ReflectionClass($namespace.'\\'.$controller);
            $controller_methods = $controller_class->getMethods(\ReflectionMethod::IS_PUBLIC);
            $data = [
                'status' => 'success',
                'message' => $push_message,
                'data' => $controller_methods,
            ];

            return response()->json($data, 200);

        } catch(\Exception $e) {
            $data = [
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => NULL,
            ];
            return response()->json($data, 400);
        }
    }

}