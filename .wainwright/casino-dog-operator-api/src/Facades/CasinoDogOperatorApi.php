<?php

namespace Wainwright\CasinoDogOperatorApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Wainwright\CasinoDogOperatorApi\CasinoDogOperatorApi
 */
class CasinoDogOperatorApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Wainwright\CasinoDogOperatorApi\CasinoDogOperatorApi::class;
    }
}
