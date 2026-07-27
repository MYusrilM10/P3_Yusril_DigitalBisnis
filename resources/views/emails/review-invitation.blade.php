<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beri Ulasan - AmikomEventHub</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #4f46e5; margin: 0; padding: 40px 20px; color: #ffffff; }
        .container { max-width: 480px; margin: 0 auto; width: 100%; }
        .header-text { text-align: center; margin-bottom: 30px; }
        .header-text h1 { font-size: 26px; font-weight: 900; margin: 0 0 8px 0; }
        .header-text p { color: #e0e7ff; margin: 0; font-size: 14px; }
        .review-card { background-color: #ffffff; color: #0f172a; border-radius: 30px; padding: 36px 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); text-align: center; }
        .event-title { color: #4f46e5; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; margin: 0 0 8px 0; }
        .event-name { font-size: 22px; font-weight: 900; margin: 0 0 6px 0; color: #0f172a; }
        .event-date { color: #64748b; font-size: 13px; margin: 0 0 24px 0; }
        .stars { font-size: 32px; color: #facc15; margin: 8px 0 18px 0; letter-spacing: 4px; }
        .message { color: #475569; font-size: 14px; line-height: 1.6; margin: 0 0 28px 0; }
        .cta-button { display: inline-block; background-color: #4f46e5; color: #ffffff !important; padding: 16px 36px; border-radius: 14px; font-size: 15px; font-weight: 800; text-decoration: none; box-shadow: 0 6px 14px rgba(79, 70, 229, 0.35); }
        .cta-button:hover { background-color: #4338ca; }
        .footer-note { margin-top: 28px; color: #94a3b8; font-size: 11px; line-height: 1.5; }
        .divider { border-top: 1px solid #e2e8f0; margin: 24px 0 20px 0; }
        .badge { display: inline-block; background-color: #eef2ff; color: #4f46e5; padding: 6px 14px; border-radius: 999px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-text">
            <h1>Bagaimana Pengalaman Anda?</h1>
            <p>Beri rating & testimoni untuk membantu penyelenggara dan calon pembeli lainnya.</p>
        </div>

        <div class="review-card">
            <span class="badge">Ulasan Pasca-Acara</span>
            <p class="event-title" style="margin-top: 16px;">Acara yang Anda hadiri</p>
            <h2 class="event-name">{{ $event->title }}</h2>
            <p class="event-date">
                {{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }} &middot; {{ $event->location }}
            </p>

            <div class="stars">&#9733; &#9733; &#9733; &#9733; &#9733;</div>

            <p class="message">
                Hai <strong>{{ $transaction->customer_name }}</strong>, terima kasih sudah hadir!
                Ulasan Anda akan sangat berarti bagi <strong>{{ $event->organization?->name ?? 'penyelenggara' }}</strong>
                dan calon pembeli di acara selanjutnya.
            </p>

            <a href="{{ $reviewUrl }}" class="cta-button">
                Beri Ulasan Sekarang
            </a>

            <div class="divider"></div>
            <p class="footer-note">
                Link ini khusus untuk pesanan <strong>{{ $transaction->order_id }}</strong> dan berlaku 30 hari.<br>
                Jika tombol tidak berfungsi, salin link berikut ke browser:<br>
                <span style="word-break: break-all; color: #4f46e5;">{{ $reviewUrl }}</span>
            </p>
        </div>
    </div>
</body>
</html>
