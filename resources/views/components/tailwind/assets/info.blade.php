@props(['asset' => $asset])
<div>

    <div class="ml-4 grid grid-cols-1 md:grid-cols-2 md:gap-2">
        <x-tailwind.dl>
            <x-tailwind.dl.item flex>
                <x-slot:label class="font-light">Status</x-slot:label>
                @if($asset->assetstatus->deployable)
                    <x-tailwind.icons.check-icon-filled/>
                @else
                    {{--                    <x-tailwind.icons.check-icon-filled class="text-yellow"--}}
                    yellow
                @endif
                {{ $asset->assetstatus->name }}
            </x-tailwind.dl.item>
            <x-tailwind.dl.item label="Serial">{{ $asset->serial }}</x-tailwind.dl.item>
            <x-tailwind.dl.item label="Category">{{ $asset->model->category->name }}</x-tailwind.dl.item>
            <x-tailwind.dl.item label="Model">{{ $asset->model->name }}</x-tailwind.dl.item>
            <x-tailwind.dl.item label="Model No.">{{ $asset->model->model_number }}</x-tailwind.dl.item>
            <x-tailwind.dl.item label="BYOD" flex>
                @if($asset->byod)
                    <x-tailwind.icons.check-icon/>
                    Yes
                @else
                    <x-tailwind.icons.x-icon/>
                    No
                @endif
            </x-tailwind.dl.item>
        </x-tailwind.dl>

        <div class="grid-cols-subgrid row-span-3 mr-4 md:justify-self-end">
            <img alt="model image" class="h-72"
                 src={{ $asset->model->getImageUrl() }} >
            @if($asset->assigned_to)
                <x-tailwind.button>Checkin Asset</x-tailwind.button>
            @else
                <x-tailwind.button>Checkout Asset</x-tailwind.button>
            @endif
            <x-tailwind.button>
                Edit Asset
            </x-tailwind.button>
            <x-tailwind.button>Clone Asset</x-tailwind.button>
            <x-tailwind.button>Audit Asset</x-tailwind.button>
            <x-tailwind.button class="mt-6 bg-deletered">Delete Asset</x-tailwind.button>
        </div>
    </div>
</div>
