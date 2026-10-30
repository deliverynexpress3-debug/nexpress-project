<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $details['title'] }}</title>
    <style>
        body {
            background: #f4f6f8;
            font-family: Arial, Helvetica, sans-serif;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 680px;
            margin: auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        .header {
            background: #28a745;
            padding: 20px;
            text-align: center;
            color: #fff;
        }

        .content {
            padding: 25px 30px;
        }

        .box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }

        .box p {
            margin: 6px 0;
            font-size: 14px;
        }

        .label {
            font-weight: bold;
        }

        .footer {
            background: #f1f5f9;
            padding: 15px 30px;
            text-align: right;
            font-size: 13px;
            color: #777;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 22px;
            background: #28a745;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    {{-- ================= CUSTOMER MAIL ================= --}}
    @if ($details['details']['type'] == 'send-customer')

        <div class="container">
            <div class="header">
                <h2>{{ $details['title'] }} 🎉</h2>
            </div>

            <div class="content">
                <h3>Hello {{ $details['details']['customer_name'] }},</h3>

                <p>
                    Thank you for your order! Your order has been placed successfully.
                    Below are your order details.
                </p>

                <div class="box">
                    <p>
                        <span class="label">Order ID:</span>
                        {{ is_array($details['details']['group_order_id'])
                            ? implode(',', $details['details']['group_order_id'])
                            : $details['details']['group_order_id'] }}
                    </p>

                    <p>
                        <span class="label">Total Amount:</span>
                        {{ Currency_Code() }}{{ $details['details']['total_amount'] }}
                    </p>

                    <p>
                        <span class="label">Payment Status:</span>
                        <strong>
                            {{ $details['details']['payment_status'] == 1 ? 'Pending' : 'Paid' }}
                        </strong>
                    </p>
                </div>

                <p>
                    We will notify you once your order is accepted and out for delivery.
                </p>
            </div>

            <div class="footer">
                {{ __('label.best_regards') }},<br>
                {{ App_Name() }} Team
            </div>
        </div>

    @endif

    {{-- ================= SELLER MAIL ================= --}}
    @if ($details['details']['type'] == 'send-seller')

        <div class="container">
            <div class="header" style="background:#007bff;">
                <h2>{{$details['title']}} 🛍️</h2>
            </div>

            <div class="content">
                <h3>Hello {{ $details['details']['seller_name'] ?? '' }},</h3>

                <p>
                    You have received a new order from customer
                    <strong>{{ $details['details']['customer_name'] }}</strong>.
                </p>

                <div class="box">
                    <p>
                        <span class="label">Order ID:</span>
                        {{ $details['details']['group_order_id'] }}
                    </p>

                    <p>
                        <span class="label">Order Amount:</span>
                        {{ Currency_Code() }}{{ $details['details']['total_amount'] }}
                    </p>

                    <p>
                        <span class="label">Payment Status:</span>
                        <strong>
                            {{ $details['details']['payment_status'] == 1 ? 'Pending' : 'Paid' }}
                        </strong>
                    </p>
                </div>

                <p>
                    Please login to your seller panel and process this order as soon as possible.
                </p>

            </div>

            <div class="footer">
                Regards,<br>
                {{ App_Name() }} Team
            </div>
        </div>

    @endif

</body>

</html>
