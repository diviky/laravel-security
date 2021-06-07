<?php

declare(strict_types=1);

namespace Diviky\Security\Console;

use Diviky\Security\Models\LoginHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:auth:history-clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear old records from the authentication log';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->comment('Clearing authentication log...');

        $days = config('security.older');
        $from = Carbon::now()->subDays($days)->format('Y-m-d H:i:s');

        LoginHistory::where('created_at', '<', $from)->delete();

        $this->info('Authentication log cleared successfully.');
    }
}
