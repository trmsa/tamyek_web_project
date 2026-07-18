<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    @foreach ($products as $product)
        <url>
            <loc>{{ urldecode(url(route('products.show', ['id' => $product->id]), $product->name)) }}</loc>
            <lastmod>{{ $product->updated_at }}</lastmod>
            <changefreq>always</changefreq>
            <priority>1</priority>
            <image:image>
                <image:loc>
                    {{ asset($product->images[0]) }}
                </image:loc>
                <image:title>{{ $product->name }}</image:title>
            </image:image>
        </url>
    @endforeach
</urlset>
