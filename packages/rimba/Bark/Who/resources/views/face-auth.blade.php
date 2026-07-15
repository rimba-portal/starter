@php
    $staffNo = $getStaffNo();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="faceVerificationComponent()"
        class="flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50/80 p-3 dark:border-gray-800 dark:bg-gray-900/40">
        {{-- Status --}}
        <div class="flex flex-col">
            <span class="text-[10px] font-medium text-gray-500" x-text="referenceStatus"></span>

            <span class="text-[10px] font-semibold text-primary-600 dark:text-primary-400" x-text="cameraStatus"></span>
        </div>
        <div class="text-danger">
            STAFF: {{ $getStaffNo() }}
        </div>
        {{-- Camera --}}
        <div class="relative">
            <div
                class="h-24 w-24 overflow-hidden rounded-lg border border-gray-200 bg-black shadow-inner dark:border-gray-700">
                <video x-ref="video" autoplay muted playsinline
                    class="h-full w-full scale-x-[-1] object-cover"></video>
            </div>

            {{-- Reference Image --}}
            <div
                class="absolute -bottom-1 -right-1 h-10 w-10 overflow-hidden rounded border-2 border-white bg-gray-100 shadow dark:bg-gray-800">
                <template x-if="photoSrc">
                    <img id="referenceImage">

                <template x-if="! photoSrc">
                    <div class="flex h-full items-center justify-center text-[8px] text-gray-400">
                        No ID
                    </div>
                </template>
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
                    await this.startCamera();
                    await window.faceModelsReady;

                    this.referenceStatus = 'Ready';

                    this.$watch(
                        () => this.$wire.data.staff_no,
                        async (staffNo) => {
                            if (!staffNo) {
                                return;
                            }

                            this.photoSrc = `/pic/${staffNo}`;
                            this.referenceStatus = 'Loading ID...';

                            await this.$nextTick();
                            await this.loadReferenceDescriptor();
                        }
                    );
                },

                async startCamera() {
                    this.cameraStatus = 'Starting...';

                    await this.$nextTick();

                    try {
                        this.stream = await navigator.mediaDevices.getUserMedia({
                            video: {
                                width: 160,
                                height: 160,
                                facingMode: 'user',
                            },
                        });

                        this.$refs.video.srcObject = this.stream;
                        await this.$refs.video.play();

                        this.cameraStatus = 'Live';
                    } catch (error) {
                        console.error(error);
                        this.cameraStatus = 'Blocked ❌';
                    }
                },

                async loadReferenceDescriptor() {
                    const img = this.$refs.refImage;

                    if (!img) {
                        return;
                    }

                    if (!img.complete) {
                        await new Promise(resolve => {
                            img.onload = resolve;
                        });
                    }

                    const detection = await faceapi
                        .detectSingleFace(
                            img,
                            new faceapi.TinyFaceDetectorOptions()
                        )
                        .withFaceLandmarks()
                        .withFaceDescriptor();

                    if (!detection) {
                        this.referenceStatus = 'No Face ❌';
                        return;
                    }

                    this.targetDescriptor = detection.descriptor;
                    this.referenceStatus = 'Reference Ready ✅';

                    this.detectLoop();
                },

                async detectLoop() {
                    if (!this.targetDescriptor || !this.$refs.video) {
                        return;
                    }

                    const detection = await faceapi
                        .detectSingleFace(
                            this.$refs.video,
                            new faceapi.TinyFaceDetectorOptions()
                        )
                        .withFaceLandmarks()
                        .withFaceDescriptor();

                    if (detection) {
                        const distance = faceapi.euclideanDistance(
                            this.targetDescriptor,
                            detection.descriptor
                        );
                        console.log('img src', img.src);

                        console.log('img size', img.naturalWidth, img.naturalHeight);
                        if (distance <= 0.55) {
                            this.cameraStatus = 'Match ✅';

                            this.stream?.getTracks().forEach(track => track.stop());

                            this.$wire.faceMatched();

                            return;
                        }

                        this.cameraStatus = `Dist: ${distance.toFixed(2)}`;
                    } else {
                        this.cameraStatus = 'Scanning...';
                    }

                    requestAnimationFrame(() => this.detectLoop());
                },
            }));
        });
    </script>
@endonce
