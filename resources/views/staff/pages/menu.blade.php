<x-filament-panels::page>
    <div class="flex flex-col gap-y-6">
        
        <div class="overflow-x-auto pb-2 -mx-4 px-4 sm:mx-0 sm:px-0">
            <x-filament::tabs>
                {{-- Static "All" Tab --}}
                <x-filament::tabs.item
                    :active="$activeTab === 'all'"
                    wire:click="$set('activeTab', 'all')"
                >
                    All
                </x-filament::tabs.item>

                {{-- Your Enum Tabs Loop --}}
                @foreach(\App\Trees\Catalog\Enums\MenuCategory::cases() as $category)
                    <x-filament::tabs.item
                        :active="$activeTab === $category->value"
                        wire:click="$set('activeTab', '{{ $category->value }}')"
                    >
                        {{ $category->getLabel() }}
                    </x-filament::tabs.item>
                @endforeach
            </x-filament::tabs>
        </div>

        <div>
            {{ $this->table }}
        </div>
        
    </div>
</x-filament-panels::page>
