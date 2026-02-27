<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
</head>
<body style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background:#f9fafb; padding:16px;">
    <div style="max-width: 640px; margin: 0 auto; background:#ffffff; border-radius:8px; padding:20px; border:1px solid #e5e7eb;">
        <h2 style="margin-top:0; margin-bottom:8px; font-size:20px;">{{ $title }}</h2>
        <p style="margin:0 0 12px; color:#4b5563; white-space:pre-line;">
            {{ $message }}
        </p>
        <p style="margin-top:16px; font-size:12px; color:#9ca3af;">
            Email này được gửi tự động từ hệ thống Campaign Aff. Vui lòng kiểm tra sớm để tránh gián đoạn theo dõi chiến dịch.
        </p>
    </div>
</body>
</html>

