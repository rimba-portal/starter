{{-- Divider line --}}
<div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>

{{-- Calendar link in user menu --}}
<x-filament::dropdown.list.item
    icon="heroicon-o-calendar-days"
    wire:click="$dispatch('open-modal', { id: 'calendar-slideover' })"
>
    {{ __('Calendar') }}
</x-filament::dropdown.list.item>

{{-- Slideover Modal --}}
@push('modals')
    <x-filament::modal
        id="calendar-slideover"
        slideover
        width="4xl"
        :heading="__('Calendar')"
        :subheading="__('Calendar view of workdays, holidays and events.')"
        class="overflow-y-auto"
    >
        @livewire(\Bites\Calendar\Components\Calendar::class)
    </x-filament::modal>
@endpush