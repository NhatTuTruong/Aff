<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 800px; margin: 0 auto; padding: 40px 20px; }
        h1 { margin-bottom: 30px; }
        h2 { margin-top: 30px; margin-bottom: 15px; }
        p { margin-bottom: 20px; }
        footer { background: #333; color: white; padding: 20px; text-align: center; margin-top: 60px; }
        footer a { color: #fff; text-decoration: underline; margin: 0 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Privacy Policy</h1>
        <p>Last updated: {{ date('F d, Y') }}</p>
        
        <h2>Information We Collect</h2>
        <p>We collect information that you provide directly to us, including when you interact with our website.</p>
        
        <h2>How We Use Your Information</h2>
        <p>We use the information we collect to provide, maintain, and improve our services.</p>
        
        <h2>Affiliate Disclosure</h2>
        <p>This website contains affiliate links. We may earn a commission if you make a purchase through our links. This does not affect the price you pay.</p>
        
        <h2>Cookies</h2>
        <p>We use cookies to enhance your experience on our website. You can choose to disable cookies through your browser settings.</p>
        
        <h2>Contact Us</h2>
        <p>If you have any questions about this Privacy Policy, please contact us.</p>
    </div>
    <footer>
        <a href="{{ route('legal.about') }}">About</a>
        <a href="{{ route('legal.contact') }}">Contact</a>
        <a href="{{ route('legal.privacy') }}">Privacy Policy</a>
    </footer>
</body>
</html>

