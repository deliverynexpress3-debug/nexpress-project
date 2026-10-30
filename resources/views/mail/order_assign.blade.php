<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $details['title'] }}</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f4f6f8;
            font-family: Arial, Helvetica, sans-serif;
            color: #333;
        }

        .container {
            max-width: 680px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        .header {
            background: #2d8cff;
            padding: 20px;
            text-align: center;
            color: #fff;
        }

        .header h2 {
            margin: 0;
            font-size: 22px;
        }

        .content {
            padding: 25px 30px;
        }

        .content h3 {
            margin-top: 0;
            color: #333;
        }

        .info-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 15px;
            margin: 15px 0;
        }

        .info-box p {
            margin: 6px 0;
            font-size: 14px;
        }

        .label {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 14px;
            text-align: left;
        }

        table th {
            background: #f1f5f9;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 22px;
            background: #2d8cff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .footer {
            background: #f9fafb;
            padding: 15px 30px;
            font-size: 13px;
            color: #777;
            text-align: right;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- HEADER -->
        <div class="header">
            <h2>{{ $details['title'] }}</h2>
        </div>

        <!-- BODY -->
        <div class="content">
            <h3>Hello {{ $details['details']['deliveryboy_name'] }}, 👋</h3>

            <p>
                A new delivery order has been assigned to you. Please review the order details below
                and start the delivery process at the earliest.
            </p>

            <!-- ORDER DETAILS -->
            <div class="info-box">
                <p><span class="label">Order ID:</span> {{ $details['details']['group_order_id'] }}</p>
                <p><span class="label">Customer Name:</span> {{ $details['details']['customer_name'] }}</p>
                <p><span class="label">Delivery Address:</span> {{ $details['details']['delivery_address'] }}</p>
                <p><span class="label">Total Amount:</span>
                    {{ Currency_Code() }}{{ $details['details']['total_payable_amount'] }}</p>
            </div>

            @php
                $groupPickupTime = !empty($details['details']['pickup_time'])
                    ? date('j M Y, h:i A', strtotime($details['details']['pickup_time']))
                    : '-';

                $groupEstimatedTime = !empty($details['details']['estimated_delivery_time'])
                    ? date('j M Y, h:i A', strtotime($details['details']['estimated_delivery_time']))
                    : '-';

            @endphp
            <!-- ITEMS -->
            <h4>📦 Items to Pick Up</h4>

            <div class="info-box">
                <p><span class="label">Pickup Time:</span> {{ $groupPickupTime }}</p>
                <p><span class="label">Pickup Location:</span> {{ $details['details']['pickup_location'] ?? '-' }}</p>
                <p><span class="label">Estimated Delivery Time:</span> {{ $groupEstimatedTime }}</p>

            </div>
            <p>
                Please ensure timely pickup and safe delivery. If you face any issues,
                contact the support team immediately.
            </p>

        </div>

        <!-- FOOTER -->
        <div class="footer">
            {{ __('label.best_regards') }},<br>
            {{ App_Name() }} Team
        </div>

    </div>

</body>

</html>
