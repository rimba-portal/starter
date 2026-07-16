@php
    $staffNo = $getStaffNo();
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        x-data="faceVerificationComponent({
            staffNo: @js($staffNo),
            matchThreshold: 0.5,
        })"
        class="space-y-3 rounded-lg border border-gray-200 bg-gray-50/80 p-3 dark:border-gray-800 dark:bg-gray-900/40"
    >
        {{-- Debug / Status --}}
        <div class="flex items-center justify-between gap-3">
            <div class="flex flex-col">
                <span
                    class="text-[10px] font-medium text-gray-500 dark:text-gray-400"
                    x-text="referenceStatus"
                ></span>

                <span
                    class="text-[10px] font-semibold text-primary-600 dark:text-primary-400"
                    x-text="cameraStatus"
                ></span>
            </div>

            <div class="text-[10px] font-semibold text-danger-600 dark:text-danger-400">
                STAFF:
                <span x-text="staffNo || 'N/A'"></span>
            </div>
        </div>

        {{-- Face verification layout --}}
        <div class="flex items-center gap-3">
            {{-- Reference image --}}
            <div class="flex flex-col items-center gap-1">
                <div class="text-[10px] text-gray-500 dark:text-gray-400">
                    ID
                </div>

                <div class="h-24 w-24 overflow-hidden rounded-lg border border-gray-200 bg-gray-100 shadow-inner dark:border-gray-700 dark:bg-gray-800">
                    <img
                        id="referenceImage"
                        x-ref="referenceImage"
                        class="h-full w-full object-cover"
                        alt="Reference image"
                    >
                </div>
            </div>

            {{-- Camera --}}
            <div class="flex flex-col items-center gap-1">
                <div class="text-[10px] text-gray-500 dark:text-gray-400">
                    Camera
                </div>

                <div class="h-24 w-24 overflow-hidden rounded-lg border border-gray-200 bg-black shadow-inner dark:border-gray-700">
                    <video
                        x-ref="video"
                        autoplay
                        muted
                        playsinline
                        class="h-full w-full scale-x-[-1] object-cover"
                    ></video>
                </div>
            </div>

            {{-- Result --}}
            <div class="min-w-24 flex-1 rounded-lg border border-gray-200 bg-white p-2 text-xs dark:border-gray-800 dark:bg-gray-950">
                <div class="font-medium text-gray-500 dark:text-gray-400">
                    Result
                </div>

                <div
                    class="mt-1 font-semibold text-gray-950 dark:text-white"
                    x-text="resultText"
                ></div>

                <div
                    class="mt-1 font-mono text-[10px] text-gray-500 dark:text-gray-400"
                    x-show="distanceText"
                    x-text="distanceText"
                ></div>
            </div>
        </div>
    </div>
</x-dynamic-component>

@once
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

    <script>
        window.faceModelsReady ??= (async () => {
            const MODEL_URL = '/models';

            await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
            await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
            await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
        })();

        document.addEventListener('alpine:init', () => {
            Alpine.data('faceVerificationComponent', (config = {}) => ({
                staffNo: config.staffNo ?? null,
                matchThreshold: config.matchThreshold ?? 0.5,

                referenceDescriptor: null,
                videoStream: null,
                isMatched: false,
                isDetecting: false,

                referenceStatus: 'Awaiting ID',
                cameraStatus: 'Offline',
                resultText: 'Waiting',
                distanceText: null,

                async init() {
                    await this.$nextTick();

                    await this.startCamera();
                    await window.faceModelsReady;

                    this.referenceStatus = 'Models Ready';

                    if (this.staffNo) {
                        await this.loadReferenceImage(this.staffNo);
                    }

                    this.watchStaffNo();
                },

                watchStaffNo() {
                    this.$watch(
                        () => this.$wire?.data?.staff_no,
                        async (staffNo) => {
                            if (!staffNo) {
                                return;
                            }

                            if (staffNo === this.staffNo && this.referenceDescriptor) {
                                return;
                            }

                            this.staffNo = staffNo;

                            await this.loadReferenceImage(staffNo);
                        }
                    );
                },

                async loadReferenceImage(staffNo) {
                    const image = this.$refs.referenceImage;

                    if (!image) {
                        this.referenceStatus = 'Image Element Missing ❌';
                        return;
                    }

                    this.referenceDescriptor = null;
                    this.isMatched = false;
                    this.distanceText = null;
                    this.resultText = 'Loading ID';
                    this.referenceStatus = 'Loading ID...';

                    image.onload = async () => {
                        await this.loadReferenceDescriptor();
                    };

                    image.onerror = () => {
                        this.referenceStatus = 'Image Load Failed ❌';
                        this.resultText = 'No ID Image';
                    };

                    image.src = `/pic/${staffNo}`;
                },

                async loadReferenceDescriptor() {
                    const image = this.$refs.referenceImage;

                    if (!image) {
                        this.referenceStatus = 'Image Element Missing ❌';
                        return;
                    }

                    if (!image.complete || image.naturalWidth === 0) {
                        this.referenceStatus = 'Image Not Ready ❌';
                        return;
                    }

                    const detection = await faceapi
                        .detectSingleFace(
                            image,
                            new faceapi.TinyFaceDetectorOptions()
                        )
                        .withFaceLandmarks()
                        .withFaceDescriptor();

                    if (!detection) {
                        this.referenceStatus = 'No Face In ID ❌';
                        this.resultText = 'No Reference Face';
                        return;
                    }

                    this.referenceDescriptor = detection.descriptor;
                    this.referenceStatus = 'Reference Ready ✅';
                    this.resultText = 'Scanning';

                    this.startDetectionLoop();
                },

                async startCamera() {
                    const video = this.$refs.video;

                    if (!video) {
                        this.cameraStatus = 'Video Missing ❌';
                        return;
                    }

                    this.cameraStatus = 'Starting...';

                    try {
                        this.videoStream = await navigator.mediaDevices.getUserMedia({
                            video: {
                                width: 160,
                                height: 160,
                                facingMode: 'user',
                            },
                        });

                        video.srcObject = this.videoStream;

                        await video.play();

                        this.cameraStatus = 'Live';
                    } catch (error) {
                        console.error(error);

                        this.cameraStatus = 'Blocked ❌';
                        this.resultText = 'Camera Blocked';
                    }
                },

                startDetectionLoop() {
                    if (this.isDetecting) {
                        return;
                    }

                    this.isDetecting = true;

                    this.detectLoop();
                },

                async detectLoop() {
                    if (this.isMatched) {
                        this.isDetecting = false;
                        return;
                    }

                    if (!this.referenceDescriptor || !this.$refs.video) {
                        requestAnimationFrame(() => this.detectLoop());
                        return;
                    }

                    const detection = await faceapi
                        .detectSingleFace(
                            this.$refs.video,
                            new faceapi.TinyFaceDetectorOptions()
                        )
                        .withFaceLandmarks()
                        .withFaceDescriptor();

                    if (!detection) {
                        this.cameraStatus = 'Scanning...';
                        this.resultText = 'No Face';

                        requestAnimationFrame(() => this.detectLoop());
                        return;
                    }

                    const distance = faceapi.euclideanDistance(
                        this.referenceDescriptor,
                        detection.descriptor
                    );

                    this.distanceText = `Distance: ${distance.toFixed(4)}`;

                    if (distance <= this.matchThreshold) {
                        this.isMatched = true;
                        this.cameraStatus = 'Match ✅';
                        this.resultText = 'Matched';

                        this.stopCamera();

                        if (this.$wire?.faceMatched) {
                            this.$wire.faceMatched();
                        }

                        return;
                    }

                    this.cameraStatus = 'Not Match';
                    this.resultText = 'Scanning';

                    requestAnimationFrame(() => this.detectLoop());
                },

                stopCamera() {
                    if (!this.videoStream) {
                        return;
                    }

                    this.videoStream.getTracks().forEach((track) => {
                        track.stop();
                    });

                    this.videoStream = null;

                    if (this.$refs.video) {
                        this.$refs.video.pause();
                        this.$refs.video.srcObject = null;
                    }

                    this.cameraStatus = 'Stopped';
                },
            }));
        });
    </script>
@endonce