<x-tailwind.layout>
    <x-tailwind.tabs active="Info">
        <x-tailwind.tab name="Info">
            <x-tailwind.assets.info :asset="$asset"/>
        </x-tailwind.tab>
        <x-tailwind.tab name="Filament History Table">
            <livewire:filament-history-table :asset="$asset"/>
        </x-tailwind.tab>
    </x-tailwind.tabs>
</x-tailwind.layout>