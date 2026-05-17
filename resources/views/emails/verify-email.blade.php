<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    </head>
    <body style="margin:0;padding:0;font-family:Arial,Helvetica,sans-serif;background:#f6f7fb;color:#333;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td align="center">
                    <table width="600" cellpadding="0" cellspacing="0" role="presentation" style="margin:28px 0;background:#ffffff;border-radius:8px;overflow:hidden;border:1px solid #e9e9ef;">
                        <tr>
                            <td style="padding:20px 24px;border-bottom:1px solid #f1f1f6;display:flex;align-items:center;gap:12px;">
                                <img src="{{ asset('images/fekasdark.png') }}" alt="{{ config('app.name') }}" style="height:40px;display:block;">
                                <h2 style="margin:0;font-size:18px;color:#1f2937;">Verify your email</h2>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:28px 24px;">
                                <p style="margin:0 0 16px 0;font-size:15px;color:#333;">Hi {{ $user->name }},</p>
                                <p style="margin:0 0 20px 0;color:#555;line-height:1.5;">Thanks for creating an account at {{ config('app.name') }}. To activate your account, please confirm your email address by clicking the button below.</p>

                                <p style="text-align:center;margin:28px 0;">
                                    <a href="{{ $verificationUrl }}" style="display:inline-block;padding:12px 22px;background:#5b1e7e;color:#fff;border-radius:8px;text-decoration:none;font-weight:600;">Verify my email</a>
                                </p>

                                <p style="color:#666;font-size:13px;">If the button doesn't work, copy and paste the link below into your browser:</p>
                                <p style="word-break:break-all;color:#1f2937;font-size:13px;">{{ $verificationUrl }}</p>

                                <p style="margin-top:22px;color:#777;font-size:13px;">If you didn't create an account, you can ignore this email.</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:16px 24px;background:#fafafa;color:#9aa0aa;font-size:13px;text-align:center;">
                                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>