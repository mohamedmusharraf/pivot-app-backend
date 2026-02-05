<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset OTP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family: Arial, Helvetica, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8; padding:30px 0;">
    <tr>
        <td align="center">

            <!-- Card -->
            <table width="100%" max-width="600" cellpadding="0" cellspacing="0" 
                   style="background:#ffffff; border-radius:12px; padding:40px; box-shadow:0 10px 30px rgba(0,0,0,0.08); max-width:600px;">
                
                <!-- Header -->
                <tr>
                    <td align="center" style="padding-bottom:20px;">
                        <h1 style="margin:0; font-size:26px; color:#111827;">
                            🔐 Password Reset
                        </h1>
                    </td>
                </tr>

                <!-- Message -->
                <tr>
                    <td style="font-size:15px; color:#374151; line-height:1.6; padding-bottom:25px;">
                        Hi {{ $name }} 👋 <br><br>
                        We received a request to reset your password.
                        Use the OTP below to continue. This OTP is valid for <strong>10 minutes</strong>.
                    </td>
                </tr>

                <!-- OTP Box -->
                <tr>
                    <td align="center" style="padding:30px 0;">
                        <div style="
                            display:inline-block;
                            background:#f3f4f6;
                            border-radius:10px;
                            padding:18px 32px;
                            font-size:32px;
                            font-weight:bold;
                            letter-spacing:8px;
                            color:#111827;
                        ">
                            {{ $otp }}
                        </div>
                    </td>
                </tr>

                <!-- Info -->
                <tr>
                    <td style="font-size:14px; color:#6b7280; line-height:1.6; padding-bottom:25px;">
                        If you did not request a password reset, you can safely ignore this email.
                        Your account remains secure.
                    </td>
                </tr>

                <!-- Divider -->
                <tr>
                    <td style="border-top:1px solid #e5e7eb; padding-top:20px;"></td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td align="center" style="font-size:13px; color:#9ca3af;">
                        © {{ date('Y') }} Pivot App. All rights reserved.
                    </td>
                </tr>

            </table>
            <!-- End Card -->

        </td>
    </tr>
</table>

</body>
</html>
