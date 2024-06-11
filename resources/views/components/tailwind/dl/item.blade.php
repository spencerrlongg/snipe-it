@use(\Illuminate\View\ComponentSlot)

@props([
    'label',
    'flex' => false,
])

<div class="grid grid-cols-2 odd:bg-gray-200 px-2">
    @if ($label instanceof ComponentSlot)
        <dt {{ $label->attributes->class(['font-bold']) }}>{{ $label }}</dt>
    @else
        <dt class="font-bold">{{ $label }}</dt>
    @endif
    <dd @class(['flex items-center' => $flex])>
        {{ $slot }}
    </dd>
</div>
