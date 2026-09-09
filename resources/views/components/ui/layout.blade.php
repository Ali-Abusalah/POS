@props(['title' => ''])

@php
    $isDark = filament()->hasDarkMode() && filament()->hasDarkModeForced();
@endphp

@push('styles')
<style>
{!! file_get_contents(resource_path('css/pos-design.css')) !!}
</style>
@endpush

<div
    class="ui-root"
    x-data="{ dark: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
    x-init="
        $watch('dark', val => {
            document.documentElement.classList.toggle('dark', val);
            localStorage.setItem('theme', val ? 'dark' : 'light');
        });
        document.documentElement.classList.toggle('dark', dark);
    "
    :class="{ 'dark': dark }"
>
    @if($title || isset($header) || isset($actions))
    <div class="ui-flex ui-items-center ui-justify-between" style="margin-bottom: 1.25rem;">
        <div>
            @if($title)
                <h1 class="ui-page-title">{{ $title }}</h1>
            @endif
            @if(isset($subtitle) && $subtitle)
                <p class="ui-page-subtitle">{{ $subtitle }}</p>
            @endif
        </div>
        <div class="ui-flex ui-gap-sm ui-items-center">
            {{-- Theme Toggle --}}
            <button
                @click="dark = !dark"
                class="ui-btn ui-btn-ghost ui-btn-sm"
                style="padding: 0.5rem;"
                x-text="dark ? '☀️' : '🌙'"
                title="Toggle dark mode"
            ></button>
            {{ $actions ?? '' }}
        </div>
    </div>
    @endif

    {{ $slot }}
</div>
