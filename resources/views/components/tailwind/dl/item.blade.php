@props([
    'term',
    'flex' => false,
])

<dt class="font-bold pl-2">{{ $term }}</dt>
<dd @class(['flex items-center' => $flex])>{{ $slot }}</dd>
