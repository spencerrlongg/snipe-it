@props([
    'term',
    'flex' => false,
])

<div class="grid grid-cols-2 odd:bg-gray-200">
    <dt class="font-bold pl-2">{{ $term }}</dt>
    <dd @class(['flex items-center' => $flex])>{{ $slot }}</dd>
</div>
