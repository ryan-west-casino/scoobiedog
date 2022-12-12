<?php

namespace Wainwright\CasinoDogOperatorApi\Models;

use \Illuminate\Database\Eloquent\Model as Eloquent;
use DB;

class OperatorGameslist extends Eloquent  {

    protected $table = 'wainwright_operator_gameslist';
    protected $timestamp = true;
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'gid',
        'gid_extra',
        'batch',
        'slug',
        'name',
        'provider',
        'type',
        'typeRating',
        'popularity',
        'bonusbuy',
        'jackpot',
        'demoplay',
        'demolink',
        'origin_demolink',
        'source',
        'source_schema',
        'realmoney',
        'method',
        'image',
        'enabled',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'realmoney' => 'json',
        'rawobject' => 'json',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
    public static function link($slug){
        return env('APP_URL').'/api/playground/viewer?game_id='.$slug;
    }
    public static function providers(){
        $kernel = new OperatorGameslist;
        $query = $kernel->distinct()->get('provider');
        foreach($query as $provider) {
            if($provider->provider === "pragmaticplay") {
                $image_tag = 'pragmatic';
            } elseif($provider->provider === 'nolimitcity') {
                $image_tag = 'nolimit';
            } else {
                $image_tag = $provider->provider;
            }
            $provider_array[] = array(
                'id' => $provider->provider,
                'slug' => $provider->provider,
                'provider' => $provider->provider,
                'image_tag' => $image_tag,
                'name' => ucfirst($provider->provider),
                'game_count' => $kernel->where('provider', $provider->provider)->count(),
                'methods' => 'demoModding',
            );
        }
        return $provider_array;
    }

}

