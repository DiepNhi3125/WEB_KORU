
@props(['name'])

<svg {{ $attributes->class(['icon'])->merge([
    'xmlns' => 'http://www.w3.org/2000/svg',
    'viewBox' => '0 0 24 24',
    'fill' => 'none',
    'stroke' => 'currentColor',
    'stroke-width' => '1.9',
    'stroke-linecap' => 'round',
    'stroke-linejoin' => 'round',
    'aria-hidden' => 'true'
]) }}>
    @switch($name)

        {{-- 01. HERO SECTION --}}

        @case('shield-check')
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/>
            <path d="m9 12 2 2 4-4"/>
            @break

        @case('users-round')
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            @break

        {{-- 02. FEATURES SECTION --}}

        @case('alarm-clock')
            <circle cx="12" cy="13" r="8"/>
            <path d="M12 9v4l2 2M5 3 2 6M19 3l3 3"/>
            @break

        @case('clipboard-check')
            <rect x="5" y="4" width="14" height="18" rx="2"/>
            <rect x="9" y="2" width="6" height="4" rx="1"/>
            <path d="m9 14 2 2 4-4"/>
            @break

        @case('users')
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
            @break

        @case('smartphone')
            <rect x="5" y="2" width="14" height="20" rx="2"/>
            <path d="M11 18h2"/>
            @break

        @case('contact-round')
            <path d="M16 2H8a4 4 0 0 0-4 4v12a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4V6a4 4 0 0 0-4-4Z"/>
            <circle cx="12" cy="9" r="2"/>
            <path d="M8 17a4 4 0 0 1 8 0"/>
            @break

        @case('bell-ring')
            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4M2 8c0-2 1-4 3-5M22 8c0-2-1-4-3-5"/>
            @break

        {{-- 03. HOW IT WORKS SECTION --}}

        @case('user-round-plus')
            <circle cx="10" cy="8" r="4"/>
            <path d="M2 21a8 8 0 0 1 16 0M20 8v6M17 11h6"/>
            @break

        @case('calendar-days')
            <rect x="3" y="5" width="18" height="16" rx="2"/>
            <path d="M16 3v4M8 3v4M3 10h18M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/>
            @break

        @case('heart-handshake')
            <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8Z"/>
            <path d="m8 13 2 2 2-2 2 2 2-2"/>
            @break

    @endswitch
</svg>
