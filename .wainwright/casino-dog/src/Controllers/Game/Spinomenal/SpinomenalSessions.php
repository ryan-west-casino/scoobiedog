<?php
namespace Wainwright\CasinoDog\Controllers\Game\Spinomenal;
use Illuminate\Support\Facades\Http;
use Wainwright\CasinoDog\Controllers\Game\GameKernelTrait;
use Wainwright\CasinoDog\Models\Gameslist;

class SpinomenalSessions extends SpinomenalMain
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
            $select_session = $this->get_internal_session($token_internal);

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
            
            $query = $this->parse_query(explode('?', $final_url)[1]);
            $new_api_url = config('casino-dog.games.spinomenal.new_api_endpoint').$token_internal.'/'.$select_session['data']['game_id'].'/play';
            $final_url =  "https://cdn-live.spinomenal.com/external_components/play2.html?partnerId=SPIN-DEBUG&gameToken=&gameCode=SlotMachine_MajesticKing&langCode=en_US&platform=4&configFile=config_default.json&isReal=false&srvUrl=https%3a%2f%2frgs-ld-demo.spinomenal.com%2fapi&gameConfigPath=SlotMachine_MajesticKing.json&rgsUrl=".$new_api_url."&inter=0&environment=live";
            $html_content = Http::get($final_url);
            $data = [
                'origin_session' => NULL, //change this if you are catching the "real" game session token from html content and want to store it to parent session
                'html' => $html_content,
                'final_url' => $final_url,
                'query' => explode('?', $final_url)[1] ?? NULL, //query 
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
            'origin_session' => $game['origin_session'],
            'final_url' => $game['final_url'],
            'query' => $game['query'],
        ];
        
        return $response;
    }


}
