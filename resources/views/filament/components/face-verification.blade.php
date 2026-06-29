@php
$staffId = $getState();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="faceVerificationComponent()"
        @start-face-recognition.window="handleRecognitionTrigger($event.detail)"
        class="flex-row items-center gap-3 bg-gray-50/80 dark:bg-gray-900/40 p-2 border border-gray-200 dark:border-gray-800 rounded-lg">

        {{-- Reference Image --}}
        <div class="flex flex-col items-center">
            <span class="text-[9px] font-medium text-gray-400 mt-0.5 truncate max-w-[64px]" x-text="referenceStatus"></span>
            <span class="text-[9px] font-bold text-primary-600 dark:text-primary-400 mt-0.5 truncate max-w-[64px]" x-text="cameraStatus"></span>
        </div>
        {{-- Camera Stream --}}
        <div class="flex flex-col items-center">
            <div class="w-16 h-16 border border-gray-200 dark:border-gray-700 rounded-md overflow-hidden bg-black shadow-inner flex items-center justify-center">
                <video
                    x-ref="video"
                    autoplay
                    muted
                    playsinline
                    class="w-full h-full object-cover scale-x-[-1] block bg-black"></video>
                <div class="absolute bottom-4 right-4 w-1/4 aspect-square md:aspect-video rounded-lg border-2 border-white/8xl shadow-lg overflow-hidden transition-transform duration-200 hover:scale-105">
                    <template x-if="photoSrc">
                        <img
                            x-ref="refImage"
                            :src="photoSrc"
                            alt="Staff Ref"
                            hidden>
                    </template>
                    <template x-if="!photoSrc">
                        <div class="text-[9px] text-gray-400 text-center leading-tight p-1 select-none">
                            No ID
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>
</x-dynamic-component>

@once
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
    window.faceModelsReady ??= (async () => {
        await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
        await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
        await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
    })();

    document.addEventListener('alpine:init', () => {
        Alpine.data('faceVerificationComponent', () => ({
            photoSrc: null,
            targetDescriptor: null,
            referenceStatus: 'Awaiting ID',
            cameraStatus: 'Offline',
            stream: null,

            async init() {
                // 1. Instantly request device camera permissions & mount the live video loop
                await this.startCamera();

                // 2. Background load models asynchronously so the interface is never stalled
                await window.faceModelsReady;
                if (this.referenceStatus === 'Awaiting ID') {
                    this.referenceStatus = 'Ready';
                }
            },

            async startCamera() {
                this.cameraStatus = 'Starting...';
                // Wait for wizard step transition opacity / rendering changes to stabilize
                await this.$nextTick();

                try {
                    this.stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            width: 160,
                            height: 160,
                            facingMode: "user"
                        }
                    });

                    if (this.$refs.video) {
                        this.$refs.video.srcObject = this.stream;
                        // Force native stream play instruction to bypass aggressive mobile/browser power restrictions
                        this.$refs.video.play().catch(e => console.log("Playback interrupted:", e));
                        this.cameraStatus = 'Live';
                    }
                } catch (e) {
                    console.error("Camera access failed:", e);
                    this.cameraStatus = 'Blocked ❌';
                }
            },

            async handleRecognitionTrigger(detail) {
                this.photoSrc = detail.photo;
                this.referenceStatus = 'Parsing...';

                await this.$nextTick();
                await this.loadReferenceDescriptor();
            },

            async loadReferenceDescriptor() {
                const img = this.$refs.refImage;
                if (!img) return;

                if (!img.complete) {
                    await new Promise(resolve => img.onload = resolve);
                }

                const detection = await faceapi
                    .detectSingleFace(img, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (!detection) {
                    this.referenceStatus = 'No Face ❌';
                    return;
                }

                this.targetDescriptor = detection.descriptor;
                this.referenceStatus = 'Staff Image Loaded ✅';
                this.detectLoop();
            },

            async detectLoop() {
                // Safety catch: check if step changed or element dropped from view DOM hierarchy
                if (!this.targetDescriptor || !this.$refs.video) return;

                const detection = await faceapi
                    .detectSingleFace(this.$refs.video, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (detection) {
                    const distance = faceapi.euclideanDistance(
                        this.targetDescriptor,
                        detection.descriptor
                    );

                    if (distance <= 0.55) {
                        this.cameraStatus = 'Match ✅';
                        if (this.stream) {
                            this.stream.getTracks().forEach(t => t.stop());
                        }
                        this.$wire.faceMatched();
                        return;
                    }
                    this.cameraStatus = `Dist: ${distance.toFixed(2)}`;
                } else {
                    this.cameraStatus = 'Scanning...';
                }

                requestAnimationFrame(() => this.detectLoop());
            }
        }));
    });
</script>
@endonce