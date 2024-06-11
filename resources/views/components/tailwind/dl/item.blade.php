@use(\Illuminate\View\ComponentSlot)

@props([
    'title',
    'flex' => false,
])

<div class="grid grid-cols-2 odd:bg-gray-200">
    @if ($title instanceof ComponentSlot)
        <dt {{ $title->attributes->class(['font-bold pl-2']) }}>{{ $title }}</dt>
    @else
        <dt class="font-bold pl-2">{{ $title }}</dt>
    @endif
    <dd @class(['flex items-center' => $flex])>
        {{ $slot }}
    </dd>
</div>
