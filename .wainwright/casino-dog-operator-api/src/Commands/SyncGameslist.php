<?php

namespace Wainwright\CasinoDogOperatorApi\Commands;

use Illuminate\Support\Facades\Http;
use Wainwright\CasinoDogOperatorApi\Models\OperatorGameslist;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;
use Symfony\Component\Process\Process;

class SyncGameslist extends Command
{
    protected $signature = 'casino-dog-operator:sync-gameslist';
    public $description = 'Sync games from JSON.';
    public function handle()
    {
        $endpoint = config('casino-dog-operator-api.endpoints.gameslist').'?operator_key='.config('casino-dog-operator-api.access.key');
        $url_source = $this->ask('Enter URL with json object. Defaults to endpoint entered in config.', $endpoint);
        $http = Http::get($endpoint);
        if(!$http->json()) {
            dd($http);
            $this->components->error('Expecting JSON result, check documentation to see acceptable mapping.');
        }
	if(!$http->status() === 200) {
	  dd($http);
	}

        $http = json_decode($http->body(), true);
	if(isset($http['code'])) {
		if($http['code'] === 400) {
		  dd($http);
		}
	}
        $filteredArray = array_map(function($array) {
                unset($array['id']);
                return $array;
        }, $http);
        $count = collect($filteredArray)->count();

        if ($this->confirm($count . ' games found from external source. Do you wish to continue?')) {
            $import_mode = $this->ask('What import mode do you want to use? You can pick between "truncate-table+upsert", "skip-if-exist", "upsert"', 'upsert');
            if($import_mode === 'truncate-table+upsert') {
                if ($this->confirm('Are you sure you want to truncate your complete games table?')) {
                    $truncate_count = OperatorGameslist::count();
                    OperatorGameslist::truncate();
                    $this->components->info('Truncated table with ' . $truncate_count .' games.');
                }
            }

            $this->gameslist_update($filteredArray, $import_mode);

        }
            return self::SUCCESS;
    }
    public function gameslist_update($new_list, $import_mode) {
        $current_games = OperatorGameslist::all();

        foreach($new_list as $game) {
            $search_existing_game = $current_games->where('gid', $game['gid'])->first();
            if($search_existing_game) {
                if($import_mode === 'upsert') {
                OperatorGameslist::where('gid', $game['gid'])->delete();
                $this->components->info('Old record removed: '. $game['gid']);
                }
            }
            if($import_mode === 'skip-if-exist') { 
                if($search_existing_game) {
                    $this->components->info('Skipped existing game: ' . $game['gid']);
                }
            } else {
                $game['realmoney'] = '[]';
             OperatorGameslist::insert($game);
             $this->components->info('New record added: '. $game['gid']);
            }
        }
    }
}
