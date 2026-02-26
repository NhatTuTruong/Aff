<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Báo cáo chiến dịch</title>
</head>
<body style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background:#f9fafb; padding:16px;">
    <div style="max-width: 640px; margin: 0 auto; background:#ffffff; border-radius:8px; padding:20px; border:1px solid #e5e7eb;">
        <h2 style="margin-top:0; margin-bottom:8px; font-size:20px;">Báo cáo hiệu suất chiến dịch</h2>
        <p style="margin:0 0 12px; color:#4b5563;">
            Xin chào {{ $user->name ?? 'Partner' }},<br>
            Dưới đây là thống kê cho các chiến dịch của bạn trong khoảng thời gian:
        </p>
        <p style="margin:0 0 16px; color:#111827; font-weight:600;">
            {{ $summary['from']->timezone(config('app.timezone'))->format('d/m/Y H:i') }}
            &rarr;
            {{ $summary['to']->timezone(config('app.timezone'))->format('d/m/Y H:i') }}
        </p>

        <table cellpadding="8" cellspacing="0" style="width:100%; margin-bottom:16px; border-collapse:collapse; font-size:14px;">
            <tr>
                <td style="border:1px solid #e5e7eb;">Tổng Clicks</td>
                <td style="border:1px solid #e5e7eb; font-weight:600; text-align:right;">
                    {{ number_format($summary['total_clicks'] ?? 0) }}
                </td>
            </tr>
            <tr>
                <td style="border:1px solid #e5e7eb;">Tổng Views</td>
                <td style="border:1px solid #e5e7eb; font-weight:600; text-align:right;">
                    {{ number_format($summary['total_views'] ?? 0) }}
                </td>
            </tr>
            <tr>
                <td style="border:1px solid #e5e7eb;">CTR trung bình</td>
                <td style="border:1px solid #e5e7eb; font-weight:600; text-align:right;">
                    {{ number_format($summary['ctr'] ?? 0, 2) }}%
                </td>
            </tr>
        </table>

        @if(!empty($summary['campaigns']))
            <h3 style="margin:0 0 8px; font-size:16px;">Chiến dịch theo hiệu suất</h3>
            <table cellpadding="6" cellspacing="0" style="width:100%; border-collapse:collapse; font-size:13px;">
                <thead>
                    <tr>
                        <th align="left" style="border-bottom:1px solid #e5e7eb;">Chiến dịch</th>
                        <th align="left" style="border-bottom:1px solid #e5e7eb;">Cửa hàng</th>
                        <th align="right" style="border-bottom:1px solid #e5e7eb;">Clicks</th>
                        <th align="right" style="border-bottom:1px solid #e5e7eb;">Views</th>
                        <th align="right" style="border-bottom:1px solid #e5e7eb;">CTR</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($summary['campaigns'] as $row)
                        <tr>
                            <td style="border-bottom:1px solid #f3f4f6;">{{ $row['campaign'] }}</td>
                            <td style="border-bottom:1px solid #f3f4f6;">{{ $row['brand'] ?? '-' }}</td>
                            <td style="border-bottom:1px solid #f3f4f6; text-align:right;">{{ number_format($row['clicks']) }}</td>
                            <td style="border-bottom:1px solid #f3f4f6; text-align:right;">{{ number_format($row['views']) }}</td>
                            <td style="border-bottom:1px solid #f3f4f6; text-align:right;">{{ number_format($row['ctr'], 2) }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="margin-top:8px; color:#6b7280; font-size:14px;">
                Không có hoạt động nào trong khoảng thời gian này.
            </p>
        @endif

        <p style="margin-top:16px; font-size:12px; color:#9ca3af;">
            Bạn nhận được email này vì đang sử dụng hệ thống Campaign Aff. Nếu bạn không muốn nhận báo cáo nữa, hãy liên hệ quản trị viên.
        </p>
    </div>
</body>
</html>

