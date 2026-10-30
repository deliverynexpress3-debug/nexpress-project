<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $details['title'] }}</title>
    <style>
        /* 🎨 Common Color Styles */
        :root {
            --primary-color: #004AAB;
            --primary-dark: #003580;
            --text-color: #333333;
            --bg-light: #f8f8f8;
            --bg-gradient: linear-gradient(135deg, #e0e7ff, #f5f7fa);
        }

        body {
            margin: 0;
            padding: 20px;
            font-family: 'Segoe UI', sans-serif;
            background: var(--bg-gradient);
        }

        .email-container {
            max-width: 600px;
            width: 100%;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 0 auto;
        }

        .email-header {
            padding: 30px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: #ffffff;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .email-body {
            padding: 25px 30px;
            color: var(--text-color);
            font-size: 16px;
            line-height: 1.7;
        }

        .email-body strong {
            color: var(--primary-color);
        }

        .password-box {
            background-color: var(--bg-light);
            padding: 12px 18px;
            border-radius: 8px;
            display: inline-block;
            font-weight: bold;
            font-size: 18px;
            letter-spacing: 1px;
            color: var(--primary-dark);
        }
    </style>
</head>

<body>
    <table align="center" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <table class="email-container" cellpadding="0" cellspacing="0">

                    <!-- Header -->
                    <tr>
                        <td class="email-header">
                            <h1>{{ $details['title'] }}</h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td class="email-body">
                            <p>Hello {{ $details['details']['user_name'] }} 👋,</p>
                            <p>A new password has been generated for your account.</p>

                            <p>
                                <span>Your new password is:</span><br>
                                <span class="password-box">{{ $details['details']['new_password'] }}</span>
                            </p>

                            <p>
                                Please log in using this password and make sure to change it immediately for security
                                reasons.
                            </p>

                            <p>Thank you,<br><strong>{{ App_Name() }}</strong> Team</p>
                        </td>
                    </tr>


                </table>
            </td>
        </tr>
    </table>
</body>

</html>
