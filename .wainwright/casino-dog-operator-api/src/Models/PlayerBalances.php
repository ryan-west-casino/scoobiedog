<?php
namespace Wainwright\CasinoDogOperatorApi\Models;
use \Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Wainwright\CasinoDogOperatorApi\Models\OperatorTransactions;

class PlayerBalances extends Eloquent  {
    protected $table = 'wainwright_player_balances';
    protected $timestamp = true;
    protected $primaryKey = 'id';
    protected $fillable = [
        'player_id',
        'player_name',
        'currency',
        'balance',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function __construct()
    {
        $this->transactions_controller = new OperatorTransactions;
    }


    public function select_player($player_id, $currency)
    {
        $player = self::where('player_id', $player_id)->first();

        if(!$player) {
            $player = $this->create_player($player_id, $currency);
        }
        return $player;
    }


    public function create_player($player_id, $currency)
    {
        $data = [
            'player_id' => $player_id,
            'player_name' => $player_id.'-name',
            'currency' => $currency,
            'balance' => config('casino-dog-operator-api.test_settings.start_balance') ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        self::insert($data);
        return self::where('player_id', $player_id)->first();
    }

    public function select_player_balance($player_id, $currency)
    {
        $player = $this->select_player($player_id, $currency);
        return (int) $player->balance;
    }


    public function process_game($player_id, $bet, $win, $currency, $game, $callback_request)
    {
        $player = $this->select_player($player_id, $currency);
        $balance = $player->balance;

        if($bet > 0) {
            if($bet > $balance) {
                abort(400, 'Bet bigger then balance');
            }
        }

        $balance = $this->select_player_balance($player_id, $currency);
        $updateBalanceOnBet = (int) $balance - $bet;
        $updateBalanceOnWin = (int) $updateBalanceOnBet + $win;
        if($updateBalanceOnWin !== $balance) {
            $amount = number_format((($updateBalanceOnWin - $balance) / 100), 2, '.', ' ');
            $amount = str_replace('-', '', $amount);
            if($updateBalanceOnWin > $balance) {
                $data = array(
                    "id" => rand(5000, 1021021012),
                    "amount" => $amount,
                    "currency" => $currency,
                    "type" => "withdrawal",
                    "player" => $player->player_id,
                    "game" => $game,
                    "data" => $callback_request,
                );

            } else {
                $data = array(
                    "id" => rand(5000, 1021021012),
                    "amount" => $amount,
                    "currency" => $currency,
                    "type" => "deposit",
                    "player" => $player->player_id,
                    "game" => $game,
                    "data" => $callback_request,
                );
            }
        }
        $final = PlayerBalances::where('player_id', $player_id)->update(['balance' => $updateBalanceOnWin]);

        return (int) $updateBalanceOnWin;
    }

    public function transfer_funds($player_id, $currency, $amount, $type)
    {
        $balance = (int) $this->select_player_balance($player_id, $currency);

        if($type === 'credit') {
            $new_balance = (int) $balance + $amount;
        } elseif($type === 'debit') {
            $new_balance = (int) $balance - $amount;
        }

        PlayerBalances::where('player_id', $player_id)->where('currency', $currency)->update(['balance' => $new_balance]);
        return (int) $new_balance;
    }


    

}