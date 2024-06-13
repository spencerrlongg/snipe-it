<x-tailwind.layout>
    <h1 class="text-2xl">Dashboard</h1>
    <div class="grid grid-cols-2 gap-4">
        @livewire(\App\Livewire\ThisYearComponentsLicensesChart::class)
        @livewire(\App\Livewire\ThisYearCheckoutsChart::class)
    </div>

</x-tailwind.layout>