@props(['active'])
<div x-data="{
        activeTab: '{{ $active }}',
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
                    x-text="tab"
                    @click="activeTab = tab"
                    class="px-4 py-1 text-sm rounded hover:bg-buttonblue hover:text-black"
                    :class="tab == activeTab ? 'bg-gray-200 text-black' : ''"
                    role="tab"
                    :aria-selected="tab === activeTab"
            ></button>
        </template>
    </div>
    <div x-ref="tabs">
        {{ $slot }}
    </div>
</div>