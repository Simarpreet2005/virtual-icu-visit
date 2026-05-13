@props([
    'variant' => 'default',
])

@php
    $tone = $variant === 'compact' ? 'compact' : 'default';
@endphp

<svg {{ $attributes->merge(['class' => 'w-full h-auto max-w-md mx-auto']) }} viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true">
    <defs>
        <linearGradient id="hi-grad-a" x1="60" y1="40" x2="340" y2="260" gradientUnits="userSpaceOnUse">
            <stop stop-color="#0ea5e9" stop-opacity="0.18"/>
            <stop offset="1" stop-color="#6366f1" stop-opacity="0.12"/>
        </linearGradient>
        <linearGradient id="hi-grad-b" x1="120" y1="100" x2="280" y2="220" gradientUnits="userSpaceOnUse">
            <stop stop-color="#38bdf8"/>
            <stop offset="1" stop-color="#4f46e5"/>
        </linearGradient>
    </defs>
    <rect x="24" y="32" width="352" height="236" rx="28" fill="url(#hi-grad-a)" stroke="#e2e8f0" stroke-width="1.5"/>
    <rect x="56" y="64" width="200" height="140" rx="16" fill="#f8fafc" stroke="#cbd5e1" stroke-width="1.5"/>
    <rect x="280" y="72" width="72" height="72" rx="36" fill="#eff6ff" stroke="url(#hi-grad-b)" stroke-width="2"/>
    <path d="M304 108h24M316 96v24" stroke="url(#hi-grad-b)" stroke-width="3" stroke-linecap="round"/>
    <path d="M72 168c18-28 36-42 54-42s36 14 54 42c18-28 36-42 54-42s36 14 54 42" stroke="#0ea5e9" stroke-width="3" stroke-linecap="round" fill="none" opacity="0.85"/>
    <circle cx="126" cy="118" r="6" fill="#22c55e" opacity="0.9"/>
    <rect x="72" y="196" width="120" height="10" rx="5" fill="#e2e8f0"/>
    <rect x="72" y="214" width="88" height="8" rx="4" fill="#f1f5f9"/>
    @if($tone === 'default')
        <rect x="56" y="224" width="288" height="36" rx="10" fill="#ffffff" stroke="#e2e8f0"/>
        <path d="M76 242h120M76 252h80" stroke="#cbd5e1" stroke-width="4" stroke-linecap="round"/>
    @endif
</svg>
