<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cảnh báo lỗi landing/coupon</title>
</head>
<body style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background:#f9fafb; padding:16px;">
    <div style="max-width: 720px; margin: 0 auto; background:#ffffff; border-radius:8px; padding:20px; border:1px solid #e5e7eb;">
        <h2 style="margin-top:0; margin-bottom:8px; font-size:20px;">Một số landing/coupon của bạn đang gặp lỗi</h2>
        <p style="margin:0 0 12px; color:#4b5563;">
            Xin chào {{ $user->name ?? 'Partner' }},<br>
            Hệ thống vừa quét định kỳ và phát hiện một số URL landing/coupon không trả về HTTP 200.
        </p>

        <table cellpadding="6" cellspacing="0" style="width:100%; border-collapse:collapse; font-size:13px; margin-top:8px;">
            <thead>
                <tr>
                    <th align="left" style="border-bottom:1px solid #e5e7eb;">Chiến dịch</th>
                    <th align="left" style="border-bottom:1px solid #e5e7eb;">URL</th>
                    <th align="center" style="border-bottom:1px solid #e5e7eb;">Status</th>
                    <th align="left" style="border-bottom:1px solid #e5e7eb;">Lỗi</th>
                    <th align="left" style="border-bottom:1px solid #e5e7eb;">Lần check</th>
                </tr>
            </thead>
            <tbody>
                @foreach($issues as $row)
                    <tr>
                        <td style="border-bottom:1px solid #f3f4f6; padding-right:8px; max-width:160px;">{{ $row['campaign'] ?? '-' }}</td>
                        <td style="border-bottom:1px solid #f3f4f6; max-width:220px; word-break:break-all;">
                            <a href="{{ $row['full_url'] }}" style="color:#2563eb; text-decoration:none;" target="_blank" rel="noopener">
                                {{ $row['url_path'] }}
                            </a>
                        </td>
                        <td align="center" style="border-bottom:1px solid #f3f4f6;">{{ $row['status_code'] }}</td>
                        <td style="border-bottom:1px solid #f3f4f6; max-width:160px;">{{ $row['error'] ?? '—' }}</td>
                        <td style="border-bottom:1px solid #f3f4f6;">{{ $row['checked_at']->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p style="margin-top:16px; font-size:13px; color:#4b5563;">
            Bạn nên kiểm tra lại các đường dẫn này (slug, trạng thái chiến dịch, brand, affiliate URL) để tránh mất traffic và doanh thu.
        </p>

        <p style="margin-top:16px; font-size:12px; color:#9ca3af;">
            Email này được gửi tự động từ hệ thống Campaign Aff sau mỗi lần quét lỗi landing/coupon.
        </p>
    </div>
</body>
</html>

