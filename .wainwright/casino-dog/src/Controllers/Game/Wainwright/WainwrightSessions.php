<?php
namespace Wainwright\CasinoDog\Controllers\Game\Wainwright;
use Illuminate\Support\Facades\Http;
use Wainwright\CasinoDog\Controllers\Game\GameKernelTrait;
use Wainwright\CasinoDog\Models\Gameslist;
use Wainwright\CasinoDog\Controllers\Game\Wainwright\WainwrightCustomSlots;

class WainwrightSessions extends WainwrightMain
{
    use GameKernelTrait;

    public function extra_game_metadata($gid)
    {
        return false;
    }

    public function gameslist() {
        $games = new WainwrightCustomSlots;
        return collect($games->gameslist());
    }

    public function create_session(string $internal_token)
    {
        $select_session = $this->get_internal_session($internal_token);
        if($select_session['status'] !== 200) { //internal session not found
               return false;
        }

        $internal_token = $select_session['data']['token_internal'];
        $game_id = $select_session['data']['game_id_original'];

        $list = $this->gameslist();
        $select_game = $list->where('gid', $game_id)->first();
        
        $custom_kernel = new WainwrightCustomSlots;
        $game_function = $select_game['game_function'];
        $html = $custom_kernel->$game_function($internal_token);
        $response = [
            'html' => $html,
            'game_id' => $game_id,
        ];
        
        return $response;
    }


}
