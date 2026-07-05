@php
    $imageUrl = storage_image_url($post->image);
    $backUrl = news_url($post);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="robots" content="noindex">
    <x-font-preload />
    <title>{{ $post->title }} — ছবি | {{ site_name_bn() }}</title>
    @if(! empty(optional($siteMeta)->site_icon))
    <link rel="icon" href="{{ storage_image_url($siteMeta->site_icon) }}" type="image/png">
    @endif
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: #000;
        }
        .post-photo-page {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            background: #000;
        }
        .post-photo-image {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center center;
            display: block;
        }
        .post-photo-close {
            position: fixed;
            top: max(12px, env(safe-area-inset-top, 0px));
            right: max(12px, env(safe-area-inset-right, 0px));
            z-index: 30;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 9999px;
            background: #e11d48;
            color: #fff;
            font-size: 28px;
            line-height: 1;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.45);
        }
        .post-photo-close:hover { background: #be123c; }
        .post-photo-caption {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 20;
            padding: 28px 12px max(10px, env(safe-area-inset-bottom, 0px));
            background: linear-gradient(to top, rgba(0, 0, 0, 0.92) 0%, rgba(0, 0, 0, 0.55) 55%, transparent 100%);
            text-align: center;
            pointer-events: none;
        }
        .post-photo-caption a {
            pointer-events: auto;
            color: #fff;
            text-decoration: none;
            font-family: "SolaimanLipi", ui-sans-serif, sans-serif;
            font-size: 17px;
            font-weight: 400;
            font-synthesis: none;
            -webkit-text-stroke: 0.2px currentColor;
            paint-order: stroke fill;
            line-height: 1.45;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        @media (min-width: 768px) {
            .post-photo-caption a { font-size: 20px; }
        }
    </style>
</head>
<body>
    <div class="post-photo-page">
        <img
            src="{{ $imageUrl }}"
            alt="{{ $post->title }}"
            class="post-photo-image"
            decoding="async">

        <a href="{{ $backUrl }}" class="post-photo-close" aria-label="বন্ধ করুন" title="বন্ধ করুন">&times;</a>

        <div class="post-photo-caption">
            <a href="{{ $backUrl }}">{{ $post->title }}</a>
        </div>
    </div>
</body>
</html>
