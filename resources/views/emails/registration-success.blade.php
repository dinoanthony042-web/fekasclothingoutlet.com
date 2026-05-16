<div style="font-family:system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; color:#1f2937; line-height:1.6;">
    <h1 style="font-size:24px; margin-bottom:16px;">Welcome to {{ config('app.name') }}, {{ $user->name }}!</h1>
    <p style="margin-bottom:12px;">Your account has been created successfully. You can now log in and start shopping with us.</p>
    <p style="margin-bottom:16px;"><strong>Email:</strong> {{ $user->email }}</p>
    <p style="margin-bottom:24px;">Click the button below to sign in:</p>
    <a href="{{ route('login') }}" style="display:inline-block; padding:12px 20px; background:#111827; color:#ffffff; text-decoration:none; border-radius:8px;">Log in to your account</a>
    <p style="margin-top:32px; color:#6b7280;">Thanks for joining {{ config('app.name') }}.</p>
</div>
