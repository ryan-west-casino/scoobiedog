<?php
namespace Wainwright\CasinoDog\Controllers\Game\Quickspin;
use Illuminate\Support\Facades\Http;
use Wainwright\CasinoDog\Controllers\Game\GameKernelTrait;
use Wainwright\CasinoDog\Models\Gameslist;

class QuickspinSessions extends QuickspinMain
{
    use GameKernelTrait;

    public function extra_game_metadata($gid)
    {
        return false;
    }

    public function fresh_game_session($game_id, $method, $token_internal = NULL)
    {
        if($method === 'demo_method') {
            $url = $this->get_game_demolink($game_id);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $html_content = curl_exec($ch);
            $final_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            curl_close($ch);

            $exploded_url = explode('?', $final_url);
            $exploded_query = $this->parse_query($exploded_url[1]);

            //$final_html = 'https://d10zgitni74b5t.cloudfront.net/casino/games/'.$exploded_query['gameid'].'/index.html?gameid='.$exploded_query['gameid'].'&jurisdiction=CW&channel=web&moneymode=fun&partnerid=2050&ticket=demo&lang=en_US&homeurl=https%3A%2F%2Fpari-match.com%2Fen%2Fcasino%2Fslots';
            $final_html = 'https://d1oij17g4yikkz.cloudfront.net/casino/games/beastwood/index.html';
            $html_content = Http::get($final_html);
            
            $data = [
                'html' => $html_content,
                'origin_game_id' => $exploded_query['gameid'],
                'final_url' => $final_url,
                'query' => $exploded_url[1],
            ];
            return $data;
        }
        
        /* example continued play session *
            // Please check Mascot/MascotSessions.php for examples on continued play (re-connecting existing sessions).
        */


        // Add in additional grey methods here, specify the method on the internal session creation when a session is requested, don't split this here
        return 'generateSessionToken() method not supported';
    }

    public function get_game_demolink($gid) {
        $select = Gameslist::where('gid', $gid)->first();
        return $select->demolink;
    }

    public function create_session(string $internal_token)
    {
        $select_session = $this->get_internal_session($internal_token);
        if($select_session['status'] !== 200) { //internal session not found
               return false;
        }

        $internal_token = $select_session['data']['token_internal'];
        $game_id = $select_session['data']['game_id_original'];

        $game = $this->fresh_game_session($game_id, 'demo_method', $internal_token);

        /* example continued play (connect to existing game session)
            // Please check Mascot/MascotSessions.php for examples on continued play (re-connecting existing sessions).
        */

        $html_content_modify = $this->modify_game($internal_token, $game['html']); //modify the HTML content by rules specified in the Main.php configuration



        $response = [
            'html' => $html_content_modify,
            'origin_url' => $game['final_url'],
            'origin_gameid' => $game['origin_game_id'],
            'query' => $game['query'],
        ];
        
        return $response;
    }


}
