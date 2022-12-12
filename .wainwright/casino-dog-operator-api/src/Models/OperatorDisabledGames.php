<?php

namespace Wainwright\CasinoDogOperatorApi\Models;

use \Illuminate\Database\Eloquent\Model as Eloquent;
use DB;

class OperatorDisabledGames extends Eloquent  {

    protected $table = 'wainwright_operator_disabled_games';
    protected $timestamp = true;
    protected $primaryKey = 'id';

    protected $fillable = [
        'gid',
        'extra',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}

