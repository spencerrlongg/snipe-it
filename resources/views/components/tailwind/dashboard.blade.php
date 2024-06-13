@php
    use App\Models\Asset;
@endphp
<x-tailwind.layout>
    <h1 class="text-2xl">Dashboard</h1>
    <div class="grid grid-cols-3 gap-4">
        <livewire:this-year-components-licenses-chart/>
        <livewire:this-year-checkouts-chart/>
        <livewire:filament-history-table :asset="Asset::find(1)"/>
    </div>
</x-tailwind.layout>