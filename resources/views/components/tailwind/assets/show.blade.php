<x-tailwind.layout>
    <h1 class="text-2xl m-4">{{ __('admin/hardware/general.view') .' '. $asset->asset_tag }}</h1>
    <x-tailwind.tabs active="Info">
        <x-tailwind.tab name="{{ __('admin/users/general.info') }}">
            <x-tailwind.assets.info :asset="$asset"/>
        </x-tailwind.tab>
        <x-tailwind.tab name="{{ __('general.components') }}">
            <livewire:component-table :asset="$asset"/>
        </x-tailwind.tab>
        <x-tailwind.tab name="{{ __('general.licenses') }}">
            <livewire:filament-license-table :asset="$asset"/>
        </x-tailwind.tab>
        <x-tailwind.tab name="{{ __('general.history') }}">
            <livewire:filament-history-table :asset="$asset"/>
        </x-tailwind.tab>
        <x-tailwind.tab name="{{ __('general.assets') }}">
            <livewire:filamanet-asset-table :asset="$asset"/>
        </x-tailwind.tab>
    </x-tailwind.tabs>
</x-tailwind.layout>
