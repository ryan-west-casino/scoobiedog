<?php
namespace Wainwright\CasinoDogOperatorApi\Traits;
use Wainwright\CasinoDogOperatorApi\Models\PlayerBalances;
use Wainwright\CasinoDogOperatorApi\Controllers\CasinoDogCreateSessionController;
trait CasinoDogOperatorTrait
{
    //  type should be 'credit' or 'debit' (add & subtract)
    public function player_transfer_funds(string $player_id, string $currency, int $amount, string $type)
    {
        $currency = strtoupper($currency);
        $player_balance = new PlayerBalances;
        $transfer = $player_balance->transfer_funds($player_id, $currency, $amount, $type);
        return (int) $transfer;
    }

    public function create_session(string $game_slug, string $player_id, string $currency, string $mode)
    {
        $session_controller = new CasinoDogCreateSessionController;
        return $session_controller->create_session($game_slug, $player_id, $currency, $mode);
    }

    protected function replaceInFile($search, $replace, $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }

}