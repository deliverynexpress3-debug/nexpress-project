<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ $details['title'] }}</title>
</head>

<body style="margin:0;padding:0;background-color:#f4f6f8;font-family:Arial,Helvetica,sans-serif;">

    @if ($details['details']['status'] == 'approved')
        <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8;padding:20px;">
            <tr>
                <td align="center">

                    <table width="600" cellpadding="0" cellspacing="0"
                        style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 4px 10px rgba(0,0,0,0.05);">

                        <!-- Header -->
                        <tr>
                            <td style="background:#28a745;color:#ffffff;padding:20px;text-align:center;">
                                <h2 style="margin:0;">{{ $details['title'] }}</h2>
                            </td>
                        </tr>

                        <!-- Body -->
                        <tr>
                            <td style="padding:25px;color:#333333;">
                                <p style="font-size:16px;margin:0 0 10px;">
                                    Hello <strong>{{ $details['details']['user_name'] }}</strong>,
                                </p>

                                <p style="font-size:15px;line-height:1.6;margin:0 0 15px;">
                                    We’re happy to inform you that your <strong>Delivery Boy request has been
                                        approved</strong>.
                                    You can now start accepting and delivering orders.
                                </p>

                                <!-- Assigned Areas -->
                                <div
                                    style="background:#f8f9fa;border:1px solid #e0e0e0;border-radius:6px;padding:15px;margin:20px 0;">
                                    <h4 style="margin:0 0 10px;color:#28a745;">📍 Assigned Delivery Areas</h4>
                                    @foreach ($details['details']['service_area'] as $zone => $areas)
                                        <p><strong>{{ $zone }}</strong></p>
                                        <ul>
                                            @foreach ($areas as $area)
                                                <li>{{ $area }}</li>
                                            @endforeach
                                        </ul>
                                    @endforeach
                                </div>

                                <p style="font-size:14px;line-height:1.6;margin:15px 0 0;">
                                    Please make sure to stay available and keep your profile updated for smooth order
                                    assignments.
                                </p>

                                <p style="font-size:14px;margin-top:20px;">
                                    Welcome onboard! 🚚<br>
                                    <strong>{{ App_Name() }}</strong> Team
                                </p>
                            </td>
                        </tr>

                        <!-- Footer -->
                        <tr>
                            <td style="background:#f1f1f1;padding:12px;text-align:center;font-size:12px;color:#777;">
                                © {{ date('Y') }} {{ App_Name() }}. All rights reserved.
                            </td>
                        </tr>

                    </table>

                </td>
            </tr>
        </table>
    @else
        <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8;padding:20px;">
            <tr>
                <td align="center">

                    <table width="600" cellpadding="0" cellspacing="0"
                        style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 4px 10px rgba(0,0,0,0.05);">

                        <!-- Header -->
                        <tr>
                            <td style="background:#dc3545;color:#ffffff;padding:20px;text-align:center;">
                                <h2 style="margin:0;">{{ $details['title'] }}</h2>
                            </td>
                        </tr>

                        <!-- Body -->
                        <tr>
                            <td style="padding:25px;color:#333333;">
                                <p style="font-size:16px;margin:0 0 10px;">
                                    Hello <strong>{{ $details['details']['user_name'] }}</strong>,
                                </p>

                                <p style="font-size:15px;line-height:1.6;margin:0 0 15px;">
                                    We regret to inform you that your <strong>Delivery Boy request has been
                                        rejected</strong>.
                                </p>

                                <p style="font-size:14px;line-height:1.6;">
                                    This may be due to incomplete or incorrect details provided during registration.
                                    You can review your information or contact our support team for further assistance.
                                </p>

                                <p style="font-size:14px;margin-top:20px;">
                                    Thank you for your interest.<br>
                                    <strong>{{ App_Name() }}</strong> Team
                                </p>
                            </td>
                        </tr>

                        <!-- Footer -->
                        <tr>
                            <td style="background:#f1f1f1;padding:12px;text-align:center;font-size:12px;color:#777;">
                                © {{ date('Y') }} {{ App_Name() }}. All rights reserved.
                            </td>
                        </tr>

                    </table>

                </td>
            </tr>
        </table>
    @endif
</body>

</html>
