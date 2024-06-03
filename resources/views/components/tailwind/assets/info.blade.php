@props(['asset' => $asset])
<div>
    <h1 class="text-2xl m-4">View Asset {{ $asset->asset_tag }}</h1>
    <div class="ml-4 grid grid-cols-1 md:grid-cols-2 md:gap-2">
        <dl class="grid grid-cols-2 border rounded-md">
            <dt class="font-bold bg-gray-200 pl-2">Status</dt>
            <dd class="bg-gray-200 flex items-center">
                @if(@$asset->assetstatus->deployable)
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                         class="text-green size-5">
                        <path fill-rule="evenodd"
                              d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z"
                              clip-rule="evenodd"/>
                    </svg>
                @else
                    yellow
                @endif
                {{ $asset->assetstatus->name }}
            </dd>
            <dt class="font-bold pl-2">Serial</dt>
            <dd class="">{{ $asset->serial }}</dd>
            <dt class="font-bold bg-gray-200 pl-2">Category</dt>
            <dd class="bg-gray-200">{{ $asset->model->category->name }}</dd>
            <dt class="font-bold pl-2">Model</dt>
            <dd class="">{{ $asset->model->name }}</dd>
            <dt class="font-bold bg-gray-200 pl-2">Model No.</dt>
            <dd class="bg-gray-200">{{ $asset->model->model_number }}</dd>
            <dt class="font-bold pl-2">BYOD</dt>
            <dd class="flex items-center">
                @if($asset->byod)
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                         class="size-5 text-green">
                        <path fill-rule="evenodd"
                              d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z"
                              clip-rule="evenodd"/>
                    </svg>
                    Yes
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                         class="size-5 text-deletered">
                        <path fill-rule="evenodd"
                              d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z"
                              clip-rule="evenodd"/>
                    </svg>
                    No
                @endif
            </dd>
        </dl>

        <div class="grid-cols-subgrid row-span-3 mr-4 md:justify-self-end">
            <img alt="model image" class="h-72"
                 src={{ $asset->model->getImageUrl() }} >
            @if($asset->assigned_to)
                <x-tailwind.button>Checkin Asset</x-tailwind.button>
            @else
                <x-tailwind.button>Checkout Asset</x-tailwind.button>
            @endif
            <x-tailwind.button>Edit Asset</x-tailwind.button>
            <x-tailwind.button>Clone Asset</x-tailwind.button>
            <x-tailwind.button>Audit Asset</x-tailwind.button>
            <x-tailwind.button class="mt-6 bg-deletered">Delete Asset</x-tailwind.button>
        </div>
    </div>
</div>