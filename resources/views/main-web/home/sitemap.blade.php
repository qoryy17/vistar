{{-- WARN: Don't run autoformatter  --}}
@php
    header('Content-Type: application/xml; charset=utf-8');

    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
@endphp
    @foreach ($urls as $item)
        <url>
            <loc>{{ $item['loc'] }}</loc>
            <changefreq>{{ $item['changefreq'] }}</changefreq>
            <lastmod>{{ $item['lastmod'] }}</lastmod>
            <priority>{{ $item['priority'] }}</priority>
        </url>
    @endforeach
@php
    echo '</urlset>';
@endphp

