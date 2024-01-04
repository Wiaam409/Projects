<?php

namespace App\Console\Commands;

use App\Models\Drugs;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class DeleteExpiredData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-expired-medicines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Drugs::where('expires_at', '<', now())
            ->delete();
    }
}
