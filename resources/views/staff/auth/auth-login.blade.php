<x-filament-panels::page>
    <div class="max-w-xl mx-auto space-y-6">
        
        <x-filament::section heading="Staff Authentication">
            <div class="space-y-4">                
                <!-- This single line renders your inputs, grid layout, -->
                <!-- reference image SFC, and webcam stream SFC dynamically -->
                {{ $this->form }}
            </div>
        </x-filament::section>

        <!-- Dynamic Success Message Banner -->
        @if ($faceVerified)
            <x-filament::section class="border-success-500 bg-success-50 dark:bg-success-950/20">
                <div class="text-success-600 dark:text-success-400 text-lg font-bold flex items-center gap-2">
                    <span>✅ Staff Face Verification Successful</span>
                </div>
            </x-filament::section>
        @endif

    </div>
</x-filament-panels::page>
