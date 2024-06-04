@props(['active'])
<div x-data="{
        activeTab: (window.location.hash.length > 0) ? window.location.hash.substring(1) : '{{ $active }}',
        tabs: [],
        tabHeadings: [],
        getName(tab) {
            return eval(`(${tab.getAttribute('x-data')})`)['name'];
        }
      }"
     x-init="() => {
        tabs = [...$refs.tabs.children];
        tabHeadings = tabs.map(tab => getName(tab));
    }"
>
    <div class="mb-3">
        <template x-for="(tab, index) in tabHeadings" :key="index">
            <button
                    {{-- right here i think i need to add some logic on click to update url anchor - can there be multiple click events? --}}
                    x-text="tab"
                    @click="activeTab = tab"
                    class="px-4 py-1 text-sm rounded hover:bg-buttonblue hover:text-white"
                    :class="tab == activeTab ? 'text-black border-b-2 border-buttonblue' : ''"
                    role="tab"
                    :aria-selected="tab === activeTab"
            ></button>
        </template>
    </div>
    <div x-ref="tabs">
        {{ $slot }}
    </div>
</div>