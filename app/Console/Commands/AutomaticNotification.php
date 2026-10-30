<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Common;
use Illuminate\Support\Facades\Log;

class AutomaticNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:auto-noti';

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
        try {
            $common = new Common();

            $common->ReminderCartItem();
            $common->ReminderWishlistItem();
            $common->ReminderDeliveryOrders();
            $common->ActiveExpriedPromocodes();
            $common->ActiveExpriedOffers();

        } catch (\Throwable $e) {

            Log::error('Schedule Run Error: ' . $e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
