<x-filament::page>
    <div class="space-y-6">

        <x-filament::section>
            <x-slot name="heading">
                User Details
            </x-slot>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="text-sm text-gray-500">Name</div>
                    <div class="font-medium">{{ $record->name }}</div>
                </div>

                <div>
                    <div class="text-sm text-gray-500">Email</div>
                    <div class="font-medium">{{ $record->email }}</div>
                </div>

                <div>
                    <div class="text-sm text-gray-500">Created At</div>
                    <div>{{ $record->created_at }}</div>
                </div>
            </div>
        </x-filament::section>

    </div>
</x-filament::page>