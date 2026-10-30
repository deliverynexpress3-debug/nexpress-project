<!DOCTYPE html>
<html>

<head>
    <title>{{ $details['title'] }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            padding: 20px;
        }

        .card {
            background: #ffffff;
            max-width: 600px;
            margin: auto;
            border-radius: 8px;
            padding: 20px;
        }

        .header {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .order-id {
            color: #555;
            margin: 10px 0;
        }

        .status-box {
            margin-top: 15px;
            padding: 12px;
            border-radius: 5px;
            font-weight: bold;
        }

        .accepted {
            background: #e8fff3;
            color: #1e7e34;
        }

        .rejected {
            background: #ffe8e8;
            color: #c82333;
        }

        .picked {
            background: #e7f1ff;
            color: #004085;
        }

        .ontheway {
            background: #fff3cd;
            color: #856404;
        }

        .delivered {
            background: #e8fff3;
            color: #1e7e34;
        }

        .otp {
            margin-top: 15px;
            padding: 10px;
            background: #fff3cd;
            border-radius: 5px;
            font-weight: bold;
        }

        .footer {
            margin-top: 25px;
            font-size: 13px;
            color: #888;
        }
    </style>
</head>

<body>
    @if ($details['details']['status_type'] == 'delivery-side')
        <div class="card">

            <div class="header">
                {{ $details['title'] ?? '' }}
            </div>

            <div class="order-id">
                Order ID: <strong>{{ $details['details']['group_order_id'] }}</strong>
            </div>

            @if ($details['details']['send_type'] == 'seller')
                <p>Hello {{ $details['details']['seller_name'] }},</p>


                {{-- 1️⃣ Accepted --}}
                @if ($details['details']['status'] == 1)
                    <div class="status-box accepted">✅ Delivery Accepted</div>
                    <p>Delivery partner has accepted this order.</p>
                    <p>Please prepare the order for pickup.</p>
                @endif

                {{-- 2️⃣ Pickup Confirm --}}
                @if ($details['details']['status'] == 2)
                    <div class="status-box picked">📦 Order Picked Up</div>
                    <p>Delivery partner has picked up the order.</p>
                @endif

                {{-- 6️⃣ Rejected --}}
                @if ($details['details']['status'] == 6)
                    <div class="status-box rejected">❌ Delivery Rejected</div>
                    <p>Delivery partner has rejected this order.</p>
                    <p>Please assign another delivery partner.</p>
                @endif
            @elseif ($details['details']['send_type'] == 'customer')
                <p>Hello {{ $details['details']['customer_name'] ?? '' }},</p>

                @if ($details['details']['status'] == 2)
                    <div class="status-box ontheway">🚚 Order Out for Delivery</div>
                    <p>Your order has been picked up and is now out for delivery.</p>

                    <div class="otp">
                        Delivery OTP: {{ $details['details']['delivery_otp'] }}
                        <br>
                        <small>Please share this OTP only with the delivery partner.</small>
                    </div>
                @endif

                {{-- 4️⃣ Delivered --}}
                @if ($details['details']['status'] == 4)
                    <div class="status-box delivered">✅ Order Delivered</div>
                    <p>Your order has been delivered successfully.</p>
                    <p>Thank you for shopping with us!</p>
                @endif

            @endif

            <div class="footer">
                Thank you for choosing {{ App_Name() }}<br>
                {{ App_Name() }} Team
            </div>

        </div>
    @elseif($details['details']['status_type'] == 'seller-side')
        <div class="card">

            <div class="header">
                {{ $details['title'] ?? '' }}
            </div>

            <div class="order-id">
                Order ID: <strong>{{ $details['details']['group_order_id'] }}</strong>
            </div>

            {{-- ================= CUSTOMER MAIL ================= --}}

            <p>Hello {{ $details['details']['customer_name'] }},</p>

            @if ($details['details']['status'] == 2)
                <div class="status-box accepted">✅ Order Confirmed</div>
                <p>Great news! Your order has been accepted by the store and is now being prepared.</p>
            @endif

            @if ($details['details']['status'] == 3)
                <div class="status-box preparing">👨‍🍳 Preparing Your Order</div>
                <p>Your items are currently being prepared. We’ll notify you once they are ready for dispatch.</p>
            @endif

            @if ($details['details']['status'] == 4)
                <div class="status-box packed">📦 Order Packed</div>
                <p>Your order has been packed and is ready for pickup by our delivery partner.</p>
            @endif

            @if ($details['details']['status'] == 5)
                <div class="status-box out">🚚 Out for Delivery</div>
                <p>Your order is on the way! Our delivery partner is heading to your location.</p>
            @endif

            @if ($details['details']['status'] == 6)
                <div class="status-box delivered">✅ Order Delivered</div>
                <p>Your order has been delivered successfully. We hope you enjoy your purchase!</p>
            @endif

            @if ($details['details']['status'] == 7)
                <div class="status-box cancelled">⚠️ Order Cancelled</div>
                <p>Your order has been cancelled. If you have any questions, please contact support.</p>
            @endif

            @if ($details['details']['status'] == 8)
                <div class="status-box rejected">❌ Order Rejected</div>
                <p>We’re sorry. Your order was rejected by the store. If any amount was deducted, it will be refunded as
                    per policy.</p>
            @endif

            <div class="footer">
                Thank you for choosing {{ App_Name() }}<br>
                {{ App_Name() }} Team
            </div>

        </div>
    @endif

    {{-- @elseif($details['details']['status_type'] == 'panel-side')
        <div class="card">

            <div class="header">
                {{ $details['title'] ?? '' }}
            </div>

            <div class="order-id">
                Order ID: <strong>{{ $details['details']['group_order_id'] }}</strong>
            </div>

            <p>Hello {{ $details['details']['customer_name'] ?? '' }},</p>

            @if ($details['details']['status'] == 2)
                <div class="status-box accepted">✅ Order Confirmed</div>
                <p>Your order has been confirmed and is being prepared.</p>
            @endif

            @if ($details['details']['status'] == 3)
                <div class="status-box ontheway">📦 Order Shipped</div>
                <p>Your order has been shipped and will be delivered soon.</p>
            @endif

            <div class="footer">
                Thank you for choosing {{ App_Name() }}<br>
                {{ App_Name() }} Team
            </div>

        </div> --}}

</body>

</html>
