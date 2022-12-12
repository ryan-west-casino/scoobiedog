<?php
namespace Wainwright\CasinoDogOperatorApi\Middleware;

use Closure;
use Illuminate\Http\Request;

class RestrictIpAddressMiddleware
{
     // Blocked IP addresses
     public function restrictedIp(){
        return config('casino-dog-operator-api.firewall.allowed_ip');
     }

     public function ifNotDuplicated() {
        if(config('casino-dog.firewall.restrict_all_routes') !== NULL)
        {
            return true;
        }
        return false;
     }

     public function restrictedHttpsOnly() {
        return config('casino-dog-operator-api.firewall.https_only');
     }
     public function restrictedEnabled() {
        return config('casino-dog-operator-api.firewall.https_only');
     }

     /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

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


    public function handle($request, Closure $next)
    {
	/*
        if($this->ifNotDuplicated()) { //check if not already loaded through other wainwright packages
            if($this->restrictedEnabled()) {
                if (!in_array($this->getIp(), $this->restrictedIp())) {
                    return response()->json(['message' => "You are not allowed to access this site.", 'ip' => $this->getIp()]);
                }
            }
            if($this->restrictedHttpsOnly()) {
                if (!$request->isSecure()) {
                    return redirect()->secure($request->getRequestUri());
                }
            }
        }
	*/
        return $next($request);
    }
}
