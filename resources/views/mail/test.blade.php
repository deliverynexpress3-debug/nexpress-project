<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $details['title'] }}</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f7f9fb; font-family: Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 0 8px rgba(0,0,0,0.05); padding: 30px;">
                    <tr>
                        <td style="padding-bottom: 20px;">
                            <h2 style="margin: 0; font-size: 20px; color: #333;">Hello,</h2>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-bottom: 10px;">
                            <p style="margin: 0; font-size: 18px; font-weight: bold; color: #4a4a4a;">
                                {{ $details['title'] }}</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-bottom: 20px;">
                            <p style="margin: 0; font-size: 16px; color: #555;">{{ $details['body'] }}</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="border-top: 1px solid #ddd; padding-top: 20px;">
                            <p style="margin: 0; font-size: 14px; color: #777;">Best regards,</p>
                            <p style="margin: 4px 0 0; font-size: 14px; font-weight: bold; color: #333;">The
                                {{ App_Name() }} Team</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
