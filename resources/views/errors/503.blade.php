<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Đang bảo trì - Viện GREECO') }}</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #0B1F33 0%, #113F17 60%, #0B1F33 100%);
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .container {
            text-align: center;
            max-width: 600px;
        }
        .icon {
            font-size: 64px;
            margin-bottom: 1.5rem;
            animation: pulse 2s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.1); }
        }
        h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #4ade80;
        }
        p {
            font-size: 1.125rem;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 2rem;
        }
        .retry-info {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border: 1px solid rgba(74, 222, 128, 0.3);
            border-radius: 12px;
            color: #4ade80;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🔧</div>
        <h1>{{ __('Đang bảo trì hệ thống') }}</h1>
        <p>
            {{ __('Hệ thống đang được nâng cấp và bảo trì để phục vụ bạn tốt hơn.') }}
            <br>{{ __('Vui lòng quay lại sau ít phút. Xin cảm ơn sự kiên nhẫn của bạn!') }}
        </p>
        <div class="retry-info">
            {{ __('Thời gian dự kiến: vài phút') }}
        </div>
    </div>
</body>
</html>
