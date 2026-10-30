{{-- <!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 13px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            border: none !important;
            padding: 10px;
        }

        .logo img {
            width: 90px;
        }

        .title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            color: #3e4890;
        }

        .receipt-info {
            text-align: right;
        }

        .info-box {
            margin-top: 25px;
            /* background: #f9f9f9; */
            padding: 15px;
            border-radius: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background: #a8a9ab;
            color: white;
            padding: 8px;
        }

        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .total {
            text-align: right;
            font-size: 16px;
            margin-top: 15px;
            color: #3e4890;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <table class="header-table">
        <tr>

            <td class="logo" style="width:25%;">
                <img src="{{ Tab_Icon_Path_For_PDF() }}">
            </td>

            <td class="title" style="width:50%;">
                Withdrawal Receipt
            </td>

            <td class="receipt-info" style="width:25%;">
                <p><strong>Receipt No :</strong> {{ $result['withdrawal']->withdrawal_no }}</p>
                <p><strong>Date :</strong> {{ date('d M Y', strtotime($result['withdrawal']->created_at)) }}</p>
                <p><strong>Payment :</strong> {{ ucfirst($result['withdrawal']->payment_method) }}</p>
            </td>

        </tr>
    </table>


    <!-- DELIVERY BOY -->
    <div class="info-box">

        <strong>Delivery Boy Details</strong>

        <p><strong>Name :</strong> {{ $result['deliveryBoy']->user_name }}</p>
        <p><strong>Email :</strong> {{ $result['deliveryBoy']->email }}</p>
        <p><strong>Mobile :</strong>
            {{ $result['deliveryBoy']->country_code ?? '' }}
            {{ $result['deliveryBoy']->mobile_number }}
        </p>

    </div>


    <!-- SETTLEMENT TABLE -->
    <div style="margin-top:25px;">

        <strong>Settlement Orders</strong>

        <table>

            <tr>
                <th>#</th>
                <th>Order ID</th>
                <th>Commission</th>
                <th>Settlement Amount</th>
                <th>Date</th>
            </tr>

            @foreach ($result['settlements'] as $key => $s)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $s->group_order_id }}</td>
                    <td>{{ Currency_Code() }} {{ $s->commission_amount }}</td>
                    <td>{{ Currency_Code() }} {{ $s->settlement_amount }}</td>
                    <td>{{ date('d M Y', strtotime($s->created_at)) }}</td>
                </tr>
            @endforeach

        </table>

    </div>


    <!-- SUMMARY -->
    <div class="total">
        Total Orders : {{ $result['totalOrders'] }} <br>
        Total Paid : {{ Currency_Code() }} {{ $result['totalPaid'] }}
    </div>

</body>

</html> --}}

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
            color: #333;
            padding: 20px;
        }

        /* HEADER */
        .header {
            border-bottom: 2px solid #3e4890;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .logo img {
            width: 90px;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            color: #3e4890;
            text-align: center;
        }

        .receipt-meta {
            text-align: right;
            font-size: 12px;
        }

        /* BOX */
        .box {
            background: #f8f9fb;
            padding: 14px;
            border-radius: 6px;
            margin-bottom: 22px;
        }

        .box h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #3e4890;
        }

        /* TABLE */
        .table-box {
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #a8a9ab;
            color: #fff;
            padding: 10px;
            font-size: 12px;
            text-align: left;
        }

        td {
            padding: 10px;
            font-size: 12px;
            border-bottom: 1px solid #e5e5e5;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .text-right {
            text-align: right;
        }

        /* SUMMARY */
        .summary {
            width: 40%;
            float: right;
            margin-top: 10px;
        }

        .summary table td {
            padding: 6px;
            border: none;
        }

        .summary .total {
            border-top: 1px solid #ccc;
            font-weight: bold;
            color: #3e4890;
            font-size: 14px;
        }

        /* FOOTER */
        .footer {
            margin-top: 45px;
            font-size: 11px;
            color: #777;
            text-align: center;
        }

        .clear {
            clear: both;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <table class="header" width="100%">
        <tr>
            <td width="25%" class="logo">
                <img src="{{ Tab_Icon_Path_For_PDF() }}">
            </td>
            <td width="50%" class="title">
                Wallet Withdrawal Receipt
            </td>
            <td width="25%" class="receipt-meta">
                <strong>Receipt No:</strong> {{ $result['withdrawal']->withdrawal_no }}<br>
                <strong>Date:</strong> {{ date('d M Y', strtotime($result['withdrawal']->created_at)) }}<br>
                <strong>Payment:</strong> {{ ucfirst($result['withdrawal']->payment_method) }}
            </td>
        </tr>
    </table>

    <!-- DELIVERY BOY DETAILS -->
    <div class="box">
        @if($result['type'] == 'deliveryboy')
            <h3>Delivery Boy Details</h3>
            <strong>Name:</strong> {{ $result['deliveryBoy']->user_name }} <br>
            <strong>Email:</strong> {{ $result['deliveryBoy']->email }} <br>
            <strong>Mobile:</strong>
            {{ $result['deliveryBoy']->country_code ?? '' }}
            {{ $result['deliveryBoy']->mobile_number }} 
        @elseif($result['type'] == 'seller')
            <h3>Seller Details</h3>
            <strong>Name:</strong> {{ $result['seller']->user_name }} <br>
            <strong>Email:</strong> {{ $result['seller']->email }} <br>
            <strong>Mobile:</strong>
            {{ $result['seller']->country_code ?? '' }}
            {{ $result['seller']->mobile_number }} 
        @endif

    </div>

    <!-- SETTLEMENT TABLE -->
    <div class="box">
        <h3>Settlement Details</h3>

        <div class="table-box">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th class="text-right">Commission Amount</th>
                        <th class="text-right">Settlement Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($result['settlements'] as $s)
                        <tr>
                            <td>{{ $s->group_order_id }}</td>
                            <td class="text-right">
                                {{ Currency_Code() }} {{ number_format($s->commission_amount, 2) }}
                            </td>
                           
                            <td class="text-right">
                                {{ Currency_Code() }} {{ number_format($s->settlement_amount, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- SUMMARY -->
    <div class="summary">
        <table width="100%">
            <tr class="total">
                <td>Total Settlement</td>
                <td class="text-right">
                    {{ Currency_Code() }} {{ number_format($result['totalPaid'], 2) }}
                </td>
            </tr>
            <tr>
                <td>Total Orders</td>
                <td class="text-right">{{ $result['totalOrders'] }}</td>
            </tr>
            <tr>
                <td>Orders Period</td>
                <td class="text-right">
                    @if (
                        date('Y-m-d', strtotime($result['period_from'])) ==
                        date('Y-m-d', strtotime($result['period_to']))
                    )
                        {{ date('d M Y', strtotime($result['period_from'])) }}
                    @else
                        {{ date('d M Y', strtotime($result['period_from'])) }}
                        –
                        {{ date('d M Y', strtotime($result['period_to'])) }}
                    @endif
                </td>
            </tr>
            
        </table>
    </div>

    <div class="clear"></div>

    <!-- FOOTER -->
    <div class="footer">
        This is a system-generated receipt. No signature required.
    </div>

</body>

</html>
