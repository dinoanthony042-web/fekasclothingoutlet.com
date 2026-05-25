<div style="font-family: Arial, sans-serif; line-height:1.4; color:#333;">
    <h2>Hello {{ $user->name }},</h2>
    <p>You requested a password reset for your account. Click the button below to set a new password. This link will expire shortly.</p>
    <p style="margin:20px 0;">
        <a href="{{ $resetUrl }}" style="display:inline-block;padding:12px 20px;background:#5b1e7e;color:#fff;border-radius:8px;text-decoration:none;">Reset password</a>
    </p>
    <p>If you didn't request a password reset, you can safely ignore this email.</p>
    <p>— {{ config('app.name') }}</p>
</div>
