@props(['name'])

<div x-data="{ name: '{{ $name }}', show: false }" x-show="name == activeTab">
    {{ $slot }}
</div>