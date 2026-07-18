<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    @foreach ($categories as $category)
        <url>
            <loc>{{ urldecode(url(route('products_category', ['id' => $category->id]), $category->name)) }}</loc>
            <lastmod>{{ $category->updated_at }}</lastmod>
            <changefreq>always</changefreq>
            <priority>1</priority>
            <image:image>
                <image:loc>
                    {{ asset($category->image) }}
                </image:loc>
                <image:title>{{ $category->name }}</image:title>
            </image:image>
        </url>
    @endforeach
</urlset>
