<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
        }

        .invoice-container {
            width: 100%;
            padding: 20px;
            /* border: 1px solid #ddd; */
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            text-align: center;
        }

        .invoice-details {
            text-align: right;
        }

        .address-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-top: 20px;
        }

        .address-box {
            width: 35%;
            padding: 15px;
            background: #f9f9f9;

        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .invoice-table th {
            background-color: #3e4890;
            color: white;
        }

        .totals {
            text-align: right;
            margin-top: 20px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            color: #3e4890;
            padding: 10px;
        }
    </style>
</head>

<body>

    <div class="invoice-container">
        <div class="header">
            <div class="logo">
                <img src="{{ Tab_Icon_Path_For_PDF() }}" style="width:100px; height:auto;" alt="Logo">

            </div>
            <div class="invoice-details">
                <p><strong>{{ __('label.order_id') }} #:</strong> {{ $result['orders']['group_order_id'] }}
                </p>
                <p><strong>Date:</strong> {{ $result['order_date'] }}</p>
            </div>
        </div>
        <table style="width: 100%; margin-top: 20px;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding: 10px; background: #f9f9f9;">
                    <p><strong>Billing Address:</strong></p>
                    <p>{{ $result['orders']['customer']->user_name }}</p>
                    <p>{{ $result['orders']['customer']->email }}</p>
                    <p>{{ $result['orders']['customer']->country_code }}
                        {{ $result['orders']['customer']->mobile_number }}</p>
                    <p>{{ $result['orders']['customer_address']->address }}</p>
                    @php
                        // Billing Name Logic
                        $billingName = !empty($result['orders']['customer']->gst_firm_name)
                            ? $result['orders']['customer']->gst_firm_name
                            : (!empty($result['orders']['customer']->full_name)
                                ? $result['orders']['customer']->full_name
                                : $result['orders']['customer']->user_name);
                        // GST Number Check
                        $billingGST = !empty($result['orders']['customer']->gst_number)
                            ? $result['orders']['customer']->gst_number
                            : '';
                    @endphp
                    <div style="margin-top: 10px; padding-top: 8px; border-top: 1px dashed #ccc;">
                        {{-- Always show Billing Name --}}
                        <p><strong>Billing Name:</strong> {{ $billingName }}</p>
                        {{-- GSTIN Only If Available --}}
                        @if ($billingGST)
                            <p><strong>GSTIN No:</strong> {{ $billingGST }}</p>
                        @endif
                    </div>
                </td>
                <td style="width: 50%; vertical-align: top; padding: 10px; background: #f9f9f9;">
                    <p><strong>Shipping Address:</strong></p>
                    <p>{{ $result['orders']['customer']->user_name }}</p>
                    <p>{{ $result['orders']['customer']->email }}</p>
                    <p>{{ $result['orders']['customer']->country_code }}
                        {{ $result['orders']['customer']->mobile_number }}</p>
                    <p>{{ $result['orders']['customer_address']->address }}</p>
                </td>
            </tr>
        </table>

        <table class="invoice-table">
            <tr>
                <th>Order No.</th>
                <th>Item</th>
                <th>Unit</th>
                <th>Qty.</th>
                <th>Price</th>
            </tr>
            @foreach ($result['orders']->items as $value)
                <tr>

                    <td>{{ $value->order_number }}</td>
                    <td>{{ $value->item_name }}</td>
                    <td>{{ $value->item_per_unit }} </td>
                    <td>{{ $value->order_quantity }}</td>
                    <td>{{ Currency_Code() }}{{ $value->order_quantity_amount }}</td>
                </tr>
            @endforeach
        </table>

        @if (!empty($result['delivery_instructor']) && count($result['delivery_instructor']) > 0)
            <div
                style="margin-top: 25px; border: 1px solid #e5e5e5; padding: 12px 15px; border-radius: 6px; background: #fafafa;">

                <p style="margin: 0 0 12px 0; font-weight: bold; font-size: 16px;">
                    Delivery Instructions:
                </p>

                <table style="width: 100%; border-collapse: collapse;">

                    @foreach ($result['delivery_instructor'] as $inst)
                        @php
                            // URL ma thi sirf image file extract karo
                            $fileName = basename($inst['image']);

                            // PDF ne absolute path joiye
                            $pdfImagePath = storage_path('app/public/delivery_instructor/' . $fileName);

                            // Check file exists
                            $imgToShow = file_exists($pdfImagePath)
                                ? $pdfImagePath
                                : public_path('assets/imgs/no_img.png');
                        @endphp

                        <tr style="border-bottom: 1px solid #eee;">

                            <!-- IMAGE -->
                            <td style="width: 55px; padding: 8px 0; text-align: center;">
                                <img src="{{ $imgToShow }}"
                                    style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover;">
                            </td>

                            <!-- TITLE -->
                            <td style="padding-left: 10px; font-size: 14px; vertical-align: middle;">
                                {{ $inst['title'] }}
                            </td>

                        </tr>
                    @endforeach

                </table>

            </div>
        @endif

        <div class="totals">
            <p><strong>Sub Total:</strong> {{ Currency_Code() }}{{ $result['orders']['item_total'] }}</p>
            <p><strong>Delivery Charges:</strong> {{ Currency_Code() }}{{ $result['orders']['delivery_charges'] }}</p>
            <p><strong>Handling Charges:</strong> {{ Currency_Code() }}{{ $result['orders']['handling_charges'] }}</p>
            <p><strong>Donation Charges:</strong> {{ Currency_Code() }}{{ $result['orders']['donation_amount'] }}</p>
            <p><strong>Discount Amount:</strong> {{ Currency_Code() }}{{ $result['orders']['discount_amount'] }}</p>
            <p><strong>Total:</strong> <span
                    style="color: #3e4890; font-size: 18px; font-weight:bold;">{{ Currency_Code() }}{{ $result['orders']['total_payable_amount'] }}</span>
            </p>
        </div>
        <div class="footer">Thank You For Your Shopping!</div>
    </div>

</body>

</html>
