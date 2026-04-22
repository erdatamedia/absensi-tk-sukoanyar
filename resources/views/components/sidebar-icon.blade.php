@props([
    'name',
    'class' => 'h-5 w-5',
])

@switch($name)
    @case('home')
        <svg class="{{ $class }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10.75 12 3l9 7.75V20a1 1 0 0 1-1 1h-5.5v-6h-5v6H4a1 1 0 0 1-1-1v-9.25Z"/>
        </svg>
        @break
    @case('scan')
        <svg class="{{ $class }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 4H5a1 1 0 0 0-1 1v2m13-3h2a1 1 0 0 1 1 1v2M4 17v2a1 1 0 0 0 1 1h2m13-3v2a1 1 0 0 1-1 1h-2M7 12h10"/>
        </svg>
        @break
    @case('clipboard')
        <svg class="{{ $class }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 4.5h6m-7 3H7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-1m-8-3h8a1 1 0 0 1 1 1v2H8v-2a1 1 0 0 1 1-1Z"/>
        </svg>
        @break
    @case('pulse')
        <svg class="{{ $class }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12h4l2-5 4 10 2-5h6"/>
        </svg>
        @break
    @case('history')
        <svg class="{{ $class }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 12a8 8 0 1 0 2.34-5.66M4 4v4h4m4-1v5l3 2"/>
        </svg>
        @break
    @case('users')
        <svg class="{{ $class }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2m18 0v-2a4 4 0 0 0-3-3.87M14 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Zm7 2a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
        </svg>
        @break
    @case('building')
        <svg class="{{ $class }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 21h16M7 21V7l5-3 5 3v14M9 10h.01M9 13h.01M9 16h.01M15 10h.01M15 13h.01M15 16h.01"/>
        </svg>
        @break
    @case('calendar')
        <svg class="{{ $class }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 3v3m8-3v3M4 9h16M5 5h14a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"/>
        </svg>
        @break
    @case('link')
        <svg class="{{ $class }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 13a5 5 0 0 0 7.07 0l1.41-1.41a5 5 0 1 0-7.07-7.07L10 5m4 6a5 5 0 0 0-7.07 0l-1.41 1.41a5 5 0 0 0 7.07 7.07L14 19"/>
        </svg>
        @break
    @case('user')
        <svg class="{{ $class }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm7 9a7 7 0 0 0-14 0"/>
        </svg>
        @break
    @case('document')
        <svg class="{{ $class }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 3.5h6l4 4V19a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 6 19V5A1.5 1.5 0 0 1 7.5 3.5Z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 3.5V8h4.5M9 12h6M9 15h6"/>
        </svg>
        @break
    @default
        <svg class="{{ $class }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="8" stroke-width="1.8"/>
        </svg>
@endswitch
