<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;

class ClearAllNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all notifications from all user accounts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command and clear all notifications from the table.
     *
     * @return int
     */
    public function handle()
    {
        echo "Deleting all records from the Notifications table...\n";
        Notification::truncate();
        if (Notification::count() == 0) {
            echo "Success!\n";
            return 0;
        }
        echo "An error occurred, a non-zero amount of records exist.\n";
        return 1;
    }
}
