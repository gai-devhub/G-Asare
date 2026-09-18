{{-- Prevents flash of wrong theme - must run before body renders --}}
<script>
(function() {
    const theme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', theme);
})();
</script>

@php
    $profile = \App\Models\Profile::first();
    $faviconUrl = asset('images/logo.png');
@endphp
<link rel="icon" href="{{ $faviconUrl }}">
@php
        $webContent = \App\Models\WebContent::first();
        $siteUrl = url('/');
        $siteName = $profile->name ?? 'G-ASARE Portfolio';
        $metaDescription = $webContent->meta_description ?? ($profile->tagline ?? 'Software engineering portfolio showcasing projects, education, and professional certifications.');
        $ogImage = asset($webContent->hero_image_url ?? ($profile->image_url ?? 'images/og-image.png'));
        $sameAs = collect($profile->social_links ?? [])->filter()->values()->all();
        $searchUrl = url('/search');
@endphp

<meta name="description" content="{{ $metaDescription }}">
<link rel="canonical" href="{{ url()->current() }}">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $siteName }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ $ogImage }}">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $siteName }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
<meta name="twitter:image" content="{{ $ogImage }}">

<!-- JSON-LD structured data for Person and WebSite (helps search engines create sitelinks) -->
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@graph": [
        {
            "@@type": "Person",
            "name": "{{ $profile->name ?? 'Gilbert Asare' }}",
            "url": "{{ $siteUrl }}",
            "sameAs": {{ json_encode($sameAs) }},
            "jobTitle": "{{ $profile->headline ?? 'Software Engineer' }}",
            "image": "{{ asset($profile->image_url ?? 'images/og-image.png') }}",
            "worksFor": { "@@type": "Organization", "name": "G-ASARE" }
        },
        {
            "@@type": "WebSite",
            "url": "{{ $siteUrl }}",
            "name": "{{ $siteName }}",
            "potentialAction": {
                "@@type": "SearchAction",
                "target": "{{ $searchUrl }}?q={search_term_string}",
                "query-input": "required name=search_term_string"
            }
        }
    ]
}
</script>
