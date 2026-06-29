<x-filament-panels::page>
    <div class="max-w-lg space-y-4">
        <x-filament::section>
            <x-slot name="heading">
                Staff Authentication
            </x-slot>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Staff ID
                    </label>
                    <input wire:model="staffId" type="text" class="w-full rounded-lg border-gray-300"
                        placeholder="Enter Staff ID">
                </div>
                <div>
                    <x-filament::button wire:click="startFaceRecognition" icon="heroicon-o-camera">
                        Face Verification
                    </x-filament::button>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                Camera
            </x-slot>
            <div class="flex gap-6 items-start">
                <div>
                    <h3 class="font-bold mb-2">Reference Image</h3>

   <img
    id="referenceImage"
    width="150"
    height="150"
    style="width:150px !important; height:150px !important;"
>

<video
    id="video"
    autoplay
    muted
    playsinline
    width="150"
    height="150"
    style="width:150px !important; height:150px !important;"
></video>
                </div>

                <div>
                    <h3 class="font-bold mb-2">Webcam</h3>

                    <video
                        id="video"
                        autoplay
                        muted
                        playsinline
                        class="h-[20px] rounded-xl border"></video>
                </div>
            </div>
            <div id="result" class="mt-4 text-lg font-bold">
                Waiting...
            </div>
        </x-filament::section>

        @if ($faceVerified)
        <x-filament::section>
            <div class="text-success-600 text-xl font-bold">
                ✅ Face Verification Success
            </div>
        </x-filament::section>
        @endif

    </div>
</x-filament-panels::page>


<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

<script>
    let referenceImage;
    let resultText;
    let video;
    let videoStream;
    let referenceDescriptor = null;

    async function loadModels() {
        const MODEL_URL = "/models";
        await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
        await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
        await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
        console.log("Models loaded");
    }

    async function startCamera() {
        videoStream = await navigator.mediaDevices.getUserMedia({
            video: true,
        });
        video.srcObject = videoStream;
        console.log("Camera started");
    }

    function stopCamera() {
        if (!videoStream) {
            return;
        }
        videoStream.getTracks().forEach((track) => track.stop());
        console.log("Camera stopped");
    }

    async function detectLoop() {
        if (!referenceDescriptor) {
            requestAnimationFrame(detectLoop);
            return;
        }
        const detection = await faceapi
            .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
            .withFaceLandmarks()
            .withFaceDescriptor();
        if (detection) {
            const distance = faceapi.euclideanDistance(
                referenceDescriptor,
                detection.descriptor,
            );
            const matched = distance <= 0.45;
            resultText.innerHTML = matched ?
                `✅ Match (${distance.toFixed(4)})` :
                `❌ Not Match (${distance.toFixed(4)})`;
            if (matched) {
                stopCamera();
                Livewire.dispatch("faceMatched");
                return;
            }
        } else {
            resultText.innerHTML = "No face detected";
        }
        requestAnimationFrame(detectLoop);
    }

    document.addEventListener("livewire:navigated", async () => {
        video = document.getElementById("video");
        referenceImage = document.getElementById("referenceImage");
        resultText = document.getElementById("result");
        await loadModels();
        await startCamera();
        detectLoop();
        Livewire.on("start-face-recognition", async (event) => {
            console.log(event);
            console.log("start-face-recognition received", event);
            referenceImage.src = event.photo;
            referenceImage.onload = async () => {
                const detection = await faceapi
                    .detectSingleFace(
                        referenceImage,
                        new faceapi.TinyFaceDetectorOptions(),
                    )
                    .withFaceLandmarks()
                    .withFaceDescriptor();
                if (!detection) {
                    resultText.innerHTML = "Reference face not found";
                    return;
                }
                referenceDescriptor = detection.descriptor;
                resultText.innerHTML = "Reference loaded ✅";
            };
        });
    });
</script>