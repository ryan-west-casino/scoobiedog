<?php
namespace Wainwright\CasinoDogOperatorApi\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class PlaygroundGate
{
    public function handle(Request $request, Closure $next)
    {
        if(config('casino-dog-operator-api.playground') === '1') {
            $path = request()->path();
            if(str_contains($path, 'playground')) {
                abort(403, 'Disabled.');
            }
        }
        return $next($request);
    }
}
