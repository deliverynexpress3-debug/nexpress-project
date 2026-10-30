<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Items;
use App\Models\Notification;
use App\Models\NotificationConfiguration;
use Illuminate\Console\Command;
use App\Models\Common;

class LowStockReminderCron extends Command
{

    protected $signature = 'lowstock-reminder-cron';
    protected $description = 'Command description';

    public function handle()
    {
        $common = new Common();

        $config = NotificationConfiguration::where('type', 'product-stock-alert')->first();

        if (!$config || $config->status != 1 || $config->send_notification != 1) {
            return;
        }

        $yesterday = date('Y-m-d', strtotime('-1 day'));

        $items = Items::where('total_stock', 0)->whereDate('updated_at', $yesterday)->get();

        foreach ($items as $item) {

            $seller = Customer::find($item->seller_id);

            if (!$seller) {
                continue;
            }

            $alreadySent = Notification::where('type', 'product-stock-alert-before')->where('seller_id', $seller->id)->where('content_id', $item->id)->exists();

            if (!$alreadySent) {

                $array = [
                    'item_name' => $item->name,
                    'item_id'   => $item->id,
                ];
                $common->SaveNotification('deliveryboy', 'product-stock-alert-before', 0, $seller['id'], 0, $item->id, $seller->deliveryBoy->device_token, $seller->deliveryBoy->device_type, $array);
            }
        }
    }
}
