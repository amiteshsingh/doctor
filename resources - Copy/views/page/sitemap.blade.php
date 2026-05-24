<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

  <url>
    <loc>https://rogisewa.com/</loc>
    <lastmod>{{ now()->toDateString() }}</lastmod>
    <changefreq>daily</changefreq>
    <priority>1.0</priority>
  </url>

  <url>
    <loc>https://rogisewa.com/about</loc>
    <changefreq>monthly</changefreq>
    <priority>0.8</priority>
  </url>

  <url>
    <loc>https://rogisewa.com/doctors</loc>
    <changefreq>daily</changefreq>
    <priority>0.9</priority>
  </url>

  <url>
    <loc>https://rogisewa.com/hospitals</loc>
    <changefreq>daily</changefreq>
    <priority>0.9</priority>
  </url>

  <url>
    <loc>https://rogisewa.com/professional-doctors</loc>
    <changefreq>daily</changefreq>
    <priority>0.8</priority>
  </url>

  <url>
    <loc>https://rogisewa.com/blog</loc>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>

  <url>
    <loc>https://rogisewa.com/contact</loc>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>

  <url>
    <loc>https://rogisewa.com/faq</loc>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>

  <url>
    <loc>https://rogisewa.com/privacy-policy</loc>
    <changefreq>yearly</changefreq>
    <priority>0.5</priority>
  </url>

  <url>
    <loc>https://rogisewa.com/terms</loc>
    <changefreq>yearly</changefreq>
    <priority>0.5</priority>
  </url>

  <url>
    <loc>https://rogisewa.com/disclaimer</loc>
    <changefreq>yearly</changefreq>
    <priority>0.5</priority>
  </url>

  @foreach($blogs as $blog)
  <url>
    <loc>https://rogisewa.com/blog/{{ $blog->slug }}</loc>
    <lastmod>{{ $blog->updated_at->toDateString() }}</lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>
  @endforeach



</urlset>
