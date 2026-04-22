@php
    $logoUrl = $schoolBranding['logo_url'] ?? null;
    $initials = $schoolBranding['initials'] ?? 'AT';
@endphp

@if($logoUrl)
    <img src="{{ $logoUrl }}" alt="Logo sekolah" {{ $attributes->merge(['class' => 'object-contain']) }}>
@else
    <span {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-2xl bg-slate-900 text-sm font-semibold text-white']) }}>{{ $initials }}</span>
@endif
