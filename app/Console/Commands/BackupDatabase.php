<?php

namespace App\Console\Commands;

use App\Http\Controllers\BackupController;
use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get every 2 Hr database backup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dbBackup = new BackupController();
        $dbBackup->getFullDatabaseBackup();
    }
}
