<?php

namespace Wainwright\CasinoDog\Models;

use \Illuminate\Database\Eloquent\Model as Eloquent;
use Wainwright\CasinoDog\Models\MetaData;
use DB;

class Gameslist extends Eloquent  {

    protected $table = 'wainwright_gameslist';
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

    public static function build_list() {
	$query = self::all()->random()->get();
	$query = json_decode($query, true);

        try {
        $random = self::where('source_schema', 'softswiss')->random(1);
        } catch(\Exception $e) {
            $random = floatval(rand(2000, 3000));
        }

	$count = 1;
        foreach($query as $game) {
            if($game['source_schema'] === "parimatch") {
                $percent = rand(92, 102);
		$game['id'] = $count;
                $random_popularity = ($random / 100) * $percent;
                $game['popularity'] = (int) number_format($random_popularity, 0, '.', '');
                $game['image'] = 'https://static-2.herokuapp.com/pm_i/'.$game['gid'].'.png';
            } else {
		$game['id'] = $count;
		$game['popularity'] = (int) $game['popularity'];
                $game['image'] = 'https://static-2.herokuapp.com/ss_i/s3/'.$game['gid'].'.png';
            }
	    $count++;
            $games[] = $game;
        }
        return $games;
    }


    public static function providers(){
        $query = Gameslist::distinct()->get('provider');

        foreach($query as $provider) {
            $provider_array[] = array(
                'id' => $provider->provider,
                'slug' => $provider->provider,
                'provider' => $provider->provider,
                'name' => ucfirst($provider->provider),
                'methods' => 'demoModding',
            );
        }
        return $provider_array;
    }

    public function gamesthumbnails(){
        return $this->belongsToMany('Wainwright\CasinoDog\Models\GamesThumbnails', 'ownedBy');
    }


}

