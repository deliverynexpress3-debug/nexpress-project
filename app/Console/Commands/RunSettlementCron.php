<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\DeliveryBoy;
use App\Models\DeliveryWithdrawal;
use App\Models\Settelment;
use Illuminate\Console\Command;

class RunSettlementCron extends Command
{

    protected $signature = 'settlement:cron';
    protected $description = 'Run settlement cron job';

    public function handle()
    {
        $settlementDays = Setting_Data()['settelment_payout_day'] ?? 0;
        $today = date('Y-m-d');

        // DELIVERY BOY PROCESS
        $boyIds = Settelment::where('type', 2)->where('status', 1)->distinct()->pluck('type_id');
        foreach ($boyIds as $id) {

            $boy = DeliveryBoy::find($id);
            if (!$boy) continue;

            $settlements = Settelment::where('type', 2)->where('type_id', $boy->id)->where('status', 1)->orderBy('created_at', 'asc')->get();
            if ($settlements->isEmpty()) continue;

            $remainingAdvance = $boy->advance_balance;
            $totalPayable = 0;
            $settlementIds = [];

            foreach ($settlements as $settlement) {

                $payoutDate = date(
                    'Y-m-d',
                    strtotime($settlement->created_at . " +$settlementDays days")
                );
                if ($today < $payoutDate) continue;

                $commission = $settlement->commission_amount;

                $adjustment = min($remainingAdvance, $commission);
                $payableAmount = $commission - $adjustment;

                $remainingAdvance -= $adjustment;

                $settlement->update([
                    'status' => 2,
                    'adjustment_amount' => $adjustment,
                    'settlement_amount' => $payableAmount
                ]);

                if ($payableAmount > 0) {
                    $totalPayable += $payableAmount;
                }

                $settlementIds[] = $settlement->id;
            }

            if ($totalPayable > 0) {
                DeliveryWithdrawal::create([
                    'withdrawal_no'  => 'SET-' . date('Y') . '-' . rand(1000, 9999),
                    'type'           => 2,
                    'type_id'        => $boy->id,
                    'total_amount'   => $totalPayable,
                    'settlement_ids' => json_encode($settlementIds),
                    'payment_method' => 'manually',
                    'status'         => 0
                ]);
            }

            $boy->advance_balance = $remainingAdvance;
            $boy->save();
        }

        // SELLER PROCESS
        $sellerIds = Settelment::where('type', 1)->where('status', 1)->distinct()->pluck('type_id');

        foreach ($sellerIds as $id) {

            $seller = Customer::find($id);
            if (!$seller) continue;

            $settlements = Settelment::where('type', 1)->where('type_id', $seller->id)->where('status', 1)->orderBy('created_at', 'asc')->get();

            if ($settlements->isEmpty()) continue;

            $totalPayable = 0;
            $settlementIds = [];

            foreach ($settlements as $settlement) {

                $payoutDate = date(
                    'Y-m-d',
                    strtotime($settlement->created_at . " +$settlementDays days")
                );

                if ($today < $payoutDate) continue;

                $settlement->update([
                    'status' => 2,
                    'settlement_amount' => $settlement->commission_amount
                ]);

                $totalPayable += $settlement->commission_amount;
                $settlementIds[] = $settlement->id;
            }

            if ($totalPayable > 0) {

                DeliveryWithdrawal::create([
                    'withdrawal_no'   => 'SET-' . date('Y') . '-' . rand(1000, 9999),
                    'type'            => 1,
                    'type_id'       => $seller->id,
                    'total_amount'    => $totalPayable,
                    'settlement_ids'  => json_encode($settlementIds),
                    'payment_method'  => 'manually',
                    'status'          => 0
                ]);

                // 2️⃣ ADD TO SELLER WALLET
                $seller->wallet_amount += $totalPayable;
                $seller->save();
            }
        }
    }
    // public function handle()
    // {
    //     $settlementDays = Setting_Data()['settelment_payout_day'] ?? 0;
    //     $today = date('Y-m-d');

    //     $boyIds = Settelment::where('type', 2)->where('status', 1)
    //         ->distinct()
    //         ->pluck('type_id');

    //     foreach ($boyIds as $id) {

    //         $boy = DeliveryBoy::find($id);
    //         if (!$boy) continue;

    //         $settlements = Settelment::where('type', 2)->where('type_id', $boy->id)
    //             ->where('status', 1)
    //             ->orderBy('created_at', 'asc')
    //             ->get();

    //         if ($settlements->isEmpty()) continue;

    //         $remainingAdvance = $boy->advance_balance;
    //         $totalPayable = 0;
    //         $settlementIds = [];

    //         foreach ($settlements as $settlement) {

    //             // Calculate payout eligible date
    //             $payoutDate = date(
    //                 'Y-m-d',
    //                 strtotime($settlement->created_at . " +$settlementDays days")
    //             );
    //             // Only process if eligible
    //             if ($today < $payoutDate) {
    //                 continue;
    //             }

    //             $commission = $settlement->commission_amount;

    //             $adjustment = min($remainingAdvance, $commission);
    //             $payableAmount = $commission - $adjustment;

    //             $remainingAdvance -= $adjustment;

    //             // Update settlement
    //             $settlement->update([
    //                 'status' => 2,
    //                 'adjustment_amount' => $adjustment,
    //                 'settlement_amount' => $payableAmount
    //             ]);

    //             if ($payableAmount > 0) {
    //                 $totalPayable += $payableAmount;
    //             }

    //             $settlementIds[] = $settlement->id;
    //         }

    //         // Create withdrawal entry if payable exists
    //         if ($totalPayable > 0) {

    //             DeliveryWithdrawal::create([
    //                 'withdrawal_no'   => 'SET-' . date('Y') . '-' . rand(1000, 9999),
    //                 'type'            => 2,
    //                 'type_id' => $boy->id,
    //                 'total_amount'    => $totalPayable,
    //                 'settlement_ids'  => json_encode($settlementIds),
    //                 'payment_method'  => 'manually',
    //                 'status'          => 0
    //             ]);
    //         }

    //         $boy->advance_balance = $remainingAdvance;
    //         $boy->save();
    //     }
    // }
}
