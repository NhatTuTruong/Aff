<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 800px; margin: 0 auto; padding: 40px 20px; }
        h1 { margin-bottom: 30px; }
        p { margin-bottom: 20px; }
        footer { background: #333; color: white; padding: 20px; text-align: center; margin-top: 60px; }
        footer a { color: #fff; text-decoration: underline; margin: 0 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Contact Us</h1>
        <p>If you have any questions or concerns, please feel free to reach out to us.</p>
        <p>Email: contact@example.com</p>
        <p>We aim to respond to all inquiries within 24-48 hours.</p>
    </div>
    <footer>
        <a href="{{ route('legal.about') }}">About</a>
        <a href="{{ route('legal.contact') }}">Contact</a>
        <a href="{{ route('legal.privacy') }}">Privacy Policy</a>
    </footer>
</body>
</html>

