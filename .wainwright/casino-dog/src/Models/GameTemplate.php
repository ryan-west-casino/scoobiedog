<?php

namespace Wainwright\CasinoDog\Models;

use \Illuminate\Database\Eloquent\Model as Eloquent;
use Wainwright\CasinoDog\Jobs\GameslistImporterProcessGame;
use Wainwright\CasinoDog\CasinoDog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
class GameTemplate extends Eloquent  {
    protected $table = 'wainwright_gametemplate';
    protected $timestamp = true;
    protected $primaryKey = 'id';

    protected $fillable = [
        'gid',
        'game_data',
        'debit',
        'credit',
        'round_id',
        'round_state',
        'game_type',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function save_game_template($gid, $game_data, $debit, $credit, $round_id, $round_state, $game_type)
    {   
        $data_morp = new CasinoDog();
        $data ??= [];
        $data = $data_morp->morph_array($data);
        $extra_data ??= [];
        $extra_data = $data_morp->morph_array($extra_data);
        $logger = new GameTemplate();
        $logger->gid = $gid;
        $logger->game_data = $game_data;
        $logger->debit = (int) $debit;
        $logger->credit = (int) $credit;
        $logger->round_id = $round_id;
        $logger->round_state = $round_state;
        $logger->game_type = $game_type;
		$logger->timestamps = true;
		$logger->save();
    }
}