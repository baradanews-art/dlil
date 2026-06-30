<?php header('Content-Type: application/xml'); ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- الصفحة الرئيسية --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toW3cString() }}</lastmod>
        <priority>1.0</priority>
    </url>
    
    {{-- المنشآت التجارية --}}
    @foreach($businesses ?? [] as $business)
    <url>
        <loc>{{ route('business.show', $business->slug) }}</loc>
        <lastmod>{{ $business->updated_at->toW3cString() }}</lastmod>
        <priority>0.8</priority>
    </url>
    @endforeach
    
    {{-- التصنيفات --}}
    @foreach($categories ?? [] as $category)
    <url>
        <loc>{{ url('/?category=' . $category->slug) }}</loc>
        <priority>0.6</priority>
    </url>
    @endforeach
    
    {{-- المواقع الجغرافية --}}
    @foreach($locations ?? [] as $location)
    <url>
        <loc>{{ url('/?location=' . $location->slug) }}</loc>
        <priority>0.6</priority>
    </url>
    @endforeach
    
    {{-- المؤسسات الرسمية --}}
    @foreach($officialEntities ?? [] as $entity)
    <url>
        <loc>{{ route('official.show', $entity->slug) }}</loc>
        <lastmod>{{ $entity->updated_at->toW3cString() }}</lastmod>
        <priority>0.7</priority>
    </url>
    @endforeach
</urlset>