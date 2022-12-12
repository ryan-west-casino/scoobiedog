<?php

namespace Wainwright\CasinoDogOperatorApi\Models;

use \Illuminate\Database\Eloquent\Model as Eloquent;
use DB;

class OperatorTransactions extends Eloquent  {

    protected $table = 'wainwright_operator_transactions';
    protected $timestamp = true;
    protected $primaryKey = 'id';

    protected $fillable = [
        'gid',
        'player_id',
        'type',
        'change',
    ];

    protected $casts = [
        'game_data' => 'json',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}

