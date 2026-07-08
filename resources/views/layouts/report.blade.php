<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 24px 28px; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1a1a1a; }
        .header { display: table; width: 100%; border-bottom: 3px solid #006400; padding-bottom: 10px; margin-bottom: 16px; }
        .header .logo-cell { display: table-cell; width: 48px; vertical-align: middle; }
        .header .logo-cell img { width: 40px; height: 40px; }
        .header .text-cell { display: table-cell; vertical-align: middle; padding-left: 10px; }
        .header .title { font-size: 18px; font-weight: bold; color: #006400; }
        .header .subtitle { font-size: 11px; color: #555; }
        .meta { font-size: 9px; color: #777; margin-bottom: 12px; }
        table.report { width: 100%; border-collapse: collapse; }
        table.report th { background: #006400; color: #ffffff; text-align: left; padding: 6px 8px; font-size: 10px; }
        table.report td { padding: 5px 8px; border-bottom: 1px solid #e0e0e0; font-size: 10px; }
        table.report tr:nth-child(even) td { background: #f4f8f4; }
        .footer { position: fixed; bottom: -10px; left: 0; right: 0; font-size: 8px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        @if ($logoDataUri = \App\Support\BrandAssets::logoDataUri())
            <div class="logo-cell"><img src="{{ $logoDataUri }}" alt="Kiyovu Sports"></div>
        @endif
        <div class="text-cell">
            <div class="title">Kiyovu Sports Association</div>
            <div class="subtitle">@yield('report_title')</div>
        </div>
    </div>
    <div class="meta">
        Generated {{ $generatedAt->format('d M Y, H:i') }} &middot; Period: {{ ucfirst($period) }}
    </div>

    @yield('content')

    <div class="footer">Kiyovu Sports Internal Rules & Records System — Confidential</div>
</body>
</html>
