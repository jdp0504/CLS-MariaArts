<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectText ?? 'Notification' }}</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background: #f4f7fa; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background: #f4f7fa; padding: 30px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.04);">
                    <tr>
                        <td style="background: linear-gradient(135deg, #0284c7, #38bdf8); padding: 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Syarikat Perniagaan Maria Arts</h1>
                            <p style="color: rgba(255,255,255,0.85); margin: 8px 0 0; font-size: 14px;">Customer Loyalty Program</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px; font-size: 15px; line-height: 1.65; color: #1f2937;">
                            {!! nl2br(e($messageContent)) !!}
                        </td>
                    </tr>
                    <tr>
                        <td style="background: #f8fafc; padding: 20px 30px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0; font-size: 12px; color: #94a3b8;">This is an automated message from Maria Arts Loyalty System. Please do not reply directly.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
