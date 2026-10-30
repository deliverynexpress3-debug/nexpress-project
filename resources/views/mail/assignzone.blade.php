<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $details['title'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            color: #333;
        }

        .content {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
        }

        .zone-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .zone-table th,
        .zone-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .zone-table th {
            background: #28a745;
            color: #fff;
        }

        .footer {
            text-align: end;
            font-size: 14px;
            color: #777;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="header"> {{ $details['title'] }}</div>
        <div class="content">

            <p>{{ __('label.dear') }} <strong>{{ $details['details']['delivery_boy_name'] }}</strong>,</p>
            <p>{{ __('label.zones_msg') }}</p>

            <table class="zone-table">
                <tr>
                    <th>{{ __('label.zone_name') }}</th>
                    <th>{{ __('label.zipcode') }}</th>
                </tr>

                @foreach ($details['details']['zone_data'] as $value)
                    <tr>
                        <td>{{ $value['zone_name'] }}</td>
                        <td>{{ $value['zipcodes'] }}</td>
                    </tr>
                @endforeach

            </table>
            <p>Please check your dashboard for more details.</p>
        </div>
        <div class="footer">
            {{ __('label.best_regards') }} ,<br>
            {{ App_Name() }} .

        </div>
    </div>
</body>

</html>
