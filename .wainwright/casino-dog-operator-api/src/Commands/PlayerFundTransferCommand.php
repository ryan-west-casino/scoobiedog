<?php

namespace Wainwright\CasinoDogOperatorApi\Commands;

use Illuminate\Support\Facades\Http;
use Wainwright\CasinoDogOperatorApi\Models\OperatorGameslist;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;
use Symfony\Component\Process\Process;
use Wainwright\CasinoDogOperatorApi\Traits\CasinoDogOperatorTrait;

class PlayerFundTransferCommand extends Command
{
    use CasinoDogOperatorTrait;

    protected $signature = 'casino-dog-operator:fund-transfer';
    public $description = 'Perform manual fund transfer from/to player balance.';
 
    public function handle()
    {
        $player_id = $this->ask('Enter player ID of target');
        $currency = $this->ask('Enter player currency', 'USD');

        $type = $this->choice(
            'Type of transfer',
            ['credit', 'debit'],
        );
        $this->line('');
        $this->info('>> Amount should be entered as integer in cents, that means if you wish to '.$type.' $1.00 you should enter 100 as amount.');
        $this->line('');
        $amount = $this->ask('How much do you want to '.$type.' from '.$player_id.'?', '10000');
        $this->line('');
        $transfer = $this->player_transfer_funds($player_id, $currency, (int) $amount, $type);
        $this->info('Transfer to '.$player_id.' seems to be succesfull.');
        $this->line('');
        $this->info('New balance: '.$transfer);

        return self::SUCCESS;
    }
}