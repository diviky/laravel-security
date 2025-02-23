<?php

declare(strict_types=1);

namespace Diviky\Security\Console\Commands;

use Illuminate\Console\Command;

class Migrate extends Command
{
    protected $signature = 'security:auth:migrate {--f|force : Force the operation to run when in production.}';

    protected $description = 'Run the database migration files';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->call('migrate', [
            '--path' => \realpath(__DIR__.'/../../../database/migrations'),
            '--realpath' => true,
            '--force' => $this->option('force'),
            '--step' => true,
        ]);
    }
}
