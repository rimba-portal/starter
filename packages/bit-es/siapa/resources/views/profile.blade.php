<x-filament-panels::page>
    @if (!auth()->user()->isFullySetup())
        <div class="p-4 mb-6 bg-yellow-50 border border-yellow-200 rounded-lg">
            <h3 class="font-semibold text-yellow-800">Required Setup</h3>
            <p class="text-sm text-yellow-700">Complete both steps to access all features.</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach ($this->getActions() as $action) {{ $action }} @endforeach
    </div>

    <x-filament::modal id="face-setup-modal" width="md">
        <x-slot name="heading">Set Up Face Verification</x-slot>
        <div class="text-center">
            <video id="cam" autoplay muted playsinline width="300" height="220" class="mx-auto border rounded"></video>
            <p id="status" class="mt-3 text-gray-600">Position your face clearly</p>
        </div>
        <x-slot name="footerActions">
            <x-filament::button id="save-face" color="primary">Save Face</x-filament::button>
        </x-slot>
    </x-filament::modal>

    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <script>
        let modelsLoaded = false, descriptor = null;
        async function loadModels() {
            await faceapi.nets.ssdMobilenetv1.loadFromUri('/models');
            await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
            await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
            modelsLoaded = true;
        }

        document.addEventListener('open-face-setup-modal', async () => {
            if (!modelsLoaded) await loadModels();
            const video = document.getElementById('cam');
            const status = document.getElementById('status');
            video.srcObject = await navigator.mediaDevices.getUserMedia({video: true});

            const check = setInterval(async () => {
                const d = await faceapi.detectSingleFace(video).withFaceLandmarks().withFaceDescriptor();
                if (d) { descriptor = d.descriptor; status.textContent = '✅ Ready to save'; status.className = 'text-green-600'; }
                else { descriptor = null; status.textContent = '❌ No clear face'; status.className = 'text-red-500'; }
            }, 1000);

            document.getElementById('save-face').onclick = async () => {
                if (!descriptor) return;
                await fetch('{{ route("profile.save-face") }}', {
                    method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'},
                    body: JSON.stringify({descriptor: Array.from(descriptor)})
                });
                window.location.reload();
            };

            document.getElementById('face-setup-modal').addEventListener('close', () => {
                clearInterval(check);
                video.srcObject?.getTracks().forEach(t => t.stop());
            });
        });
    </script>
</x-filament-panels::page>