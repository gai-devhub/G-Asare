{{-- Prevents flash of wrong theme - must run before body renders --}}
<script>
(function() {
    const theme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', theme);
})();
</script>

@php
    $faviconProfile = \App\Models\Profile::first();
    $faviconUrl = $faviconProfile && $faviconProfile->image_url ? asset($faviconProfile->image_url) : asset('images/favicon.jpg');
@endphp
<link rel="icon" href="{{ $faviconUrl }}">
