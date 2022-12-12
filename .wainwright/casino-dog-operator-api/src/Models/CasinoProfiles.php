<?php
namespace Wainwright\CasinoDogOperatorApi\Models;
use \Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Wainwright\CasinoDogOperatorApi\Models\PlayerBalances;

class CasinoProfiles extends Eloquent  {
    protected $table = 'wainwright_casino_profiles';
    protected $timestamp = true;
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'player_id',
        'currency',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function casino_profile($user_id, $currency)
    {
        $casino_profile = self::where('user_id', $user_id)->where('currency', $currency)->first();
        $player = new PlayerBalances;

        if(!$casino_profile) {
            $player_id = md5($user_id.now().$currency);
            $player_data = $player->create_player($player_id, $currency);
            $data = [
                'player_id' => $player_id,
                'user_id' => $user_id,
                'currency' => $currency,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            self::insert($data);
            $casino_profile = self::where('user_id', $user_id)->where('currency', $currency)->first();
        }

        $select_player = $player->select_player($casino_profile->player_id, $currency);

        

        $data = [
            'player_id' => $casino_profile->player_id,
            'user_id' => $user_id,
            'currency' => $currency,
            'balance' => $select_player->balance,
            'money_format' => number_format(($select_player->balance / 100), 2),
        ];

        return $data;
    }




}