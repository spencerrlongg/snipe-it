<x-tailwind.layout>
    <h1 class="text-2xl m-4">View Asset {{ $asset->asset_tag }}</h1>
    <x-tailwind.tabs active="Info">
        <x-tailwind.tab name="Info">
            <x-tailwind.assets.info :asset="$asset"/>
        </x-tailwind.tab>
        <x-tailwind.tab name="Components">
            <livewire:component-table :asset="$asset"/>
        </x-tailwind.tab>
        <x-tailwind.tab name="Licenses">
            <livewire:filament-license-table :asset="$asset"/>
        </x-tailwind.tab>
        <x-tailwind.tab name="History">
            <livewire:filament-history-table :asset="$asset"/>
        </x-tailwind.tab>
        <x-tailwind.tab name="Assets">
            <livewire:filamanet-asset-table :asset="$asset"/>
        </x-tailwind.tab>
    </x-tailwind.tabs>
</x-tailwind.layout>
