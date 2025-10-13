<?php

namespace App\Console\Commands;

use App\Models\Dream;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckOverdueDreams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dreams:check-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for scheduled dreams whose destiny date has passed and move them to cherished memories';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for overdue dreams...');

        // Find scheduled dreams whose destiny date has passed
        $overdueDreams = Dream::where('status', 'scheduled')
            ->where('destiny_date', '<=', Carbon::now())
            ->get();

        if ($overdueDreams->isEmpty()) {
            $this->info('No overdue dreams found.');
            return 0;
        }

        $count = 0;
        foreach ($overdueDreams as $dream) {
            $dream->moveToCherished();
            $count++;
            $this->info("Moved dream '{$dream->heading}' to Cherished Memories");
        }

        $this->info("Successfully moved {$count} dream(s) to Cherished Memories.");
        
        return 0;
    }
}
