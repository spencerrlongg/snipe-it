<body class="bg-gray-50">
<head>
    <title>{{ $title ?? 'Snipe IT' }}</title>
    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">
    {{--    <link rel="stylesheet" href="{{ url(mix('css/dist/all.css')) }}">--}}
    @livewireStyles
</head>


<nav class="bg-navblue text-white mb-2">
    <div class="container mx-auto px-2 md:flex items-center gap-4">
        <!-- Logo -->
        <div class="flex items-center justify-between md:w-auto w-full mr-auto">

            <a class="py-3 px-2 text-white flex font-extralight text-2xl items-center" href={{ env('APP_URL') }}>
            <img alt="logo" class="mr-2 h-8" src={{ asset('img/demo/snipe-logo.png') }}>
            Snipe-IT TW
            </a>

            <!-- mobile menu icon -->
            <div class="md:hidden flex items-center">
                <button type="button" class="mobile-menu-button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="hidden md:flex md:flex-row-reverse flex-col items-center justify-start md:space-x-1 pb-3 md:pb-0 navigation-menu md:space-x-reverse">
            <a href="#" class="py-2 px-3 block">Home</a>
            <a href="#" class="py-2 px-3 block">About</a>
            <!-- Dropdown menu -->
            <div class="relative">
                <button type="button"
                        class="dropdown-toggle py-2 px-3 hover:bg-sky-800 flex items-center gap-2 rounded">
                    <span class="pointer-events-none select-none">Services</span>
                    <svg class="w-3 h-3 pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                    </svg>
                </button>
                <div class="dropdown-menu absolute hidden bg-sky-700 text-white rounded-b-lg pb-2 w-48">
                    <a href="#" class="block px-6 py-2 hover:bg-sky-800">Web Design</a>
                    <a href="#" class="block px-6 py-2 hover:bg-sky-800">Web Development</a>
                    <a href="#" class="block px-6 py-2 hover:bg-sky-800">SEO</a>
                </div>
            </div>
            <a href="#" class="py-2 px-3 block">Contact</a>
        </div>
    </div>
</nav>
<div class="pl-2 bg-gray-50">
    {{ $slot }}
</div>
{{-- Javascript files --}}
<script src="{{ url(mix('js/dist/all.js')) }}" nonce="{{ csrf_token() }}"></script>

@stack('js')
@livewireScripts
</body>