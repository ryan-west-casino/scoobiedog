<?php

namespace Wainwright\CasinoDogOperatorApi\Commands;

use Illuminate\Console\Command;

class CasinoDogOperatorApiCommand extends Command
{
    public $signature = 'casino-dog-operator-api';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
