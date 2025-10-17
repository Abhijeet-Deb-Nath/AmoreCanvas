<?php

namespace App\Console\Commands;

use App\Models\LoveLetter;
use App\Jobs\SendLoveLetterDeliveryEmail;
use Illuminate\Console\Command;
use Carbon\Carbon;

class DeliverLoveLetters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'letters:deliver';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for love letters ready for delivery and dispatch them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for love letters ready for delivery...');

        // Find letters that are ready for delivery (scheduled time has passed and not yet delivered)
        $readyLetters = LoveLetter::readyForDelivery()->get();

        if ($readyLetters->isEmpty()) {
            $this->info('No letters ready for delivery.');
            return 0;
        }

        $count = 0;
        foreach ($readyLetters as $letter) {
            // Dispatch the job to send email and mark as delivered
            SendLoveLetterDeliveryEmail::dispatch($letter);
            $count++;
            $this->info("Dispatched delivery for letter '{$letter->title}' to {$letter->receiver->name}");
        }

        $this->info("Successfully dispatched {$count} love letter(s) for delivery.");
        
        return 0;
    }
}
