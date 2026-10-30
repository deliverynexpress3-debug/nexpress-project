<?php

namespace App\Console\Commands;

use App\Models\DailyNotification;
use App\Models\General_Setting;
use Illuminate\Console\Command;

class SendDailyNotificationEvening extends Command
{
    protected $signature = 'send-daily-notification-evening';
    protected $description = 'Send Daily Notification Evening';

    public function handle()
    {

        $settings = General_Setting::get();

        $config = [];
        foreach ($settings as $item) {
            $config[$item->key] = $item->value;
        }
        $appId = $config['c_onesignal_app_id'] ?? null;
        $restKey = $config['c_onesignal_app_id'] ?? null;

        $day = now()->day;
        $daily_notification = DailyNotification::where('day', $day)->where('type', 2)->first();

        if (!$appId || !$restKey || !$daily_notification) {
            return Command::FAILURE;
        }

        $fields = array(
            'app_id' => $appId,
            'included_segments' => array('All'),
            'data' => array("foo" => "bar"),
            'headings' => array("en" => $daily_notification->title),
            'contents' => array("en" => $daily_notification->message),
        );

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . $restKey,
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        curl_close($ch);

        return Command::SUCCESS;
    }
}
