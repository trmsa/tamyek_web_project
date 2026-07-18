<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    @foreach ($articles as $article)
        <url>
            <loc>{{ urldecode(url(route('articles.show', ['id' => $article->id]), $article->title)) }}</loc>
            <lastmod>{{ $article->updated_at }}</lastmod>
            <changefreq>always</changefreq>
            <priority>1</priority>
            <image:image>
                <image:loc>
                    {{ asset($article->images[0]) }}
                </image:loc>
                <image:title>{{ $article->title }}</image:title>
            </image:image>
        </url>
    @endforeach
</urlset>
