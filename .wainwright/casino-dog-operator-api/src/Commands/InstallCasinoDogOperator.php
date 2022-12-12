<?php

namespace Wainwright\CasinoDogOperatorApi\Commands;

use Illuminate\Support\Facades\Http;
use Wainwright\CasinoDogOperatorApi\Models\OperatorGameslist;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;
use Symfony\Component\Process\Process;
use Wainwright\CasinoDogOperatorApi\Commands\SyncGamesFromJson;

class InstallCasinoDogOperator extends Command
{
    protected $signature = 'casino-dog-operator:install';
    public $description = 'Install casino-dog gameslist.';

    public function handle()
    {
        /* Publish & run migration */
        if($this->confirm('Do you want to publish migrations?')) {
            $this->info('> Running..  "vendor:publish --tag="casino-dog-operator-api-migrations"');
            \Artisan::call('vendor:publish --tag="casino-dog-operator-api-migrations"');
        }  else {
            $this->info('.. Skipped database migrations');
        }

        /* Publish & run migration */
        if($this->confirm('Do you want to run migrations?')) {
            $this->info('> Running..  "artisan migrate"');
            \Artisan::call('migrate');
        }  else {
            $this->info('.. Skipped database migrations');
        }

        /* Publish config file*/
        if($this->confirm('Do you want to publish config to your laravel root path?')) {
            \Artisan::call('vendor:publish --tag="casino-dog-operator-api-config"');
            $this->info('> Running..  "vendor:publish --tag="casino-dog-operator-api-config"');
            $this->info('> Config published in config/casino-dog-operator-api.php');
        }  else {
            $this->info('.. Skipped publishing config');
        }

        /* API limit */
        if($this->confirm('Do you want to change the hard API request limit in RouteServiceProvider.php?')) {
            $limit = $this->ask('Enter new API limit per IP per minute on all API routes (integer)', 5000);
            $new_limit = (int) $limit;
            $this->replaceInBetweenInFile("perMinute\(", "\)", $new_limit, base_path('app/Providers/RouteServiceProvider.php'));
            $this->info('> Running..  "api limit"');
        }  else {
            $this->info('.. Skipped changing route hard limits.');
        }

        /* break */
        $this->line('');
        $this->line('');

        /* info box */
        $this->info('Completed. If you are running fresh install, use following commands to further configure:');
        $this->info('>> Run artisan "casino-dog-operator:connect-to-api" to set API url.');
        $this->info('>> Run artisan "casino-dog-operator:sync-gameslist" to synchronize the database table for operator frontend.');
        $this->line('');

        return self::SUCCESS;
    }

    public function replaceInBetweenInFile($a, $b, $replace, $path)
    {
        $file_get_contents = file_get_contents($path);
        $in_between = $this->in_between($a, $b, $file_get_contents);
        if($in_between) {
            $search_string = stripcslashes($a.$in_between.$b);
            $replace_string = stripcslashes($a.$replace.$b);
            file_put_contents($path, str_replace($search_string, $replace_string, file_get_contents($path)));
            return self::SUCCESS;
        }
        return self::SUCCESS;
    }



    public function in_between($a, $b, $data)
    {
        preg_match('/'.$a.'(.*?)'.$b.'/s', $data, $match);
        if(!isset($match[1])) {
            return false;
        }
        return $match[1];
    }

    protected function requireComposerPackages($packages)
    {
        $composer = $this->option('composer');

        if ($composer !== 'global') {
            $command = ['php', $composer, 'require'];
        }

        $command = array_merge(
            $command ?? ['composer', 'require'],
            is_array($packages) ? $packages : func_get_args()
        );

        (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }

}
