<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Dream;
use Carbon\Carbon;

class MoveToCherishedMemories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dreams:move-to-cherished';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move scheduled dreams with passed destiny dates to cherished memories';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for scheduled dreams with passed dates...');

        $passedDreams = Dream::where('status', 'scheduled')
            ->where('destiny_date', '<', Carbon::now())
            ->get();

        if ($passedDreams->isEmpty()) {
            $this->info('No dreams to move. All scheduled dreams are in the future.');
            return 0;
        }

        $count = 0;
        foreach ($passedDreams as $dream) {
            $dream->update([
                'status' => 'cherished',
                'cherished_at' => Carbon::now(),
            ]);
            $count++;
            $this->line("Moved dream #{$dream->id} ({$dream->heading}) to Cherished Memories");
        }

        $this->info("Successfully moved {$count} dream(s) to Cherished Memories! 💝");
        return 0;
    }
}
