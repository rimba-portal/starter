<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Face Recognition Test</title>
    <script defer src='https:cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js'></script>
    <script defer src='https:cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js'></script>
    <style>
        body {
            font-family: Arial;
            text-align: center;
            background: #f5f5f5;
        }

        video,
        img {
            border: 1px solid #ccc;
            border-radius: 10px;
            margin: 10px;
        }

        .container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            background: #0078d4;
            color: white;
            cursor: pointer;
        }
    </style>
</head>

<body x-data="faceRecognitionTest()" x-init="init()">
    <h2>Face Match Realtime</h2>

    <div>
        <input type=" text" x-model="filename" placeholder="Enter filename, example 153582" @keydown.enter="loadImage()">
        <button type="button" @click="loadImage()">
            Load Image
        </button>
    </div>

    <div class="container">
        {{-- Reference Image --}}
        <div>
            <h4>Reference Image</h4>

            <img x-ref="referenceImage" width="250" height="250" alt="Reference image">
        </div>

        {{-- Webcam --}}
        <div>
            <h4>Webcam</h4>

            <div class="camera-wrapper">
                <video x-ref="video" width="300" height="250" autoplay muted playsinline></video>

                <canvas x-ref="overlay" class="overlay" width="300" height="250"></canvas>
            </div>
        </div>
    </div>

    <h3 class="status" x-text="resultText"></h3>

    <div class="debug">
        <div>
            Distance:
            <span x-text="distanceText"></span>
        </div>

        <div>
            Match:
            <span x-text="matchPercentText"></span>
        </div>
    </div>

    <script>
        function faceRecognitionTest() {
            return {
                filename: '',
                resultText: 'Starting...',
                distanceText: '-',
                matchPercentText: '-',

                matchThreshold: 0.45,

                referenceDescriptor: null,
                videoStream: null,
                isDetecting: false,
                isMatched: false,

                async init() {
                    try {
                        this.resultText = 'Loading models...';

                        await this.loadModels();

                        this.resultText = 'Starting camera...';

                        await this.startCamera();

                        this.resultText = 'Ready';

                        this.$refs.video.onloadeddata = () => {
                            this.startDetectionLoop();
                        };
                    } catch (error) {
                        console.error(error);
                        this.resultText = 'Failed to initialize';
                    }
                },

                async loadModels() {
                    const MODEL_URL = '/models';

                    await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
                    await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
                    await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
                },

                async loadImage() {
                    const staffNo = this.filename.trim();

                    if (!staffNo) {
                        alert('Enter filename');
                        return;
                    }

                    this.referenceDescriptor = null;
                    this.isMatched = false;
                    this.distanceText = '-';
                    this.matchPercentText = '-';
                    this.resultText = 'Loading reference image...';

                    const image = this.$refs.referenceImage;

                    image.onload = async () => {
                        await this.loadReferenceDescriptor();
                    };

                    image.onerror = () => {
                        this.resultText = 'Failed to load reference image';
                    };

                    image.src = `/pic/${staffNo}`;
                },

                async loadReferenceDescriptor() {
                    const image = this.$refs.referenceImage;

                    if (!image.complete || image.naturalWidth === 0) {
                        this.resultText = 'Reference image not ready';
                        return;
                    }

                    this.resultText = 'Reading reference face...';

                    const detection = await faceapi
                        .detectSingleFace(
                            image,
                            new faceapi.TinyFaceDetectorOptions()
                        )
                        .withFaceLandmarks()
                        .withFaceDescriptor();

                    if (!detection) {
                        this.resultText = 'No face detected in reference image';
                        return;
                    }

                    this.referenceDescriptor = detection.descriptor;
                    this.resultText = 'Reference loaded. Scan your face.';
                },

                async startCamera() {
                    const video = this.$refs.video;

                    this.videoStream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            width: 300,
                            height: 250,
                            facingMode: 'user',
                        },
                    });

                    video.srcObject = this.videoStream;

                    await video.play();
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

                    const video = this.$refs.video;
                    const canvas = this.$refs.overlay;
                    const ctx = canvas.getContext('2d');

                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    if (!this.referenceDescriptor) {
                        requestAnimationFrame(() => this.detectLoop());
                        return;
                    }

                    const detection = await faceapi
                        .detectSingleFace(
                            video,
                            new faceapi.TinyFaceDetectorOptions()
                        )
                        .withFaceLandmarks()
                        .withFaceDescriptor();

                    if (!detection) {
                        this.resultText = 'No face detected';
                        this.distanceText = '-';
                        this.matchPercentText = '-';

                        requestAnimationFrame(() => this.detectLoop());
                        return;
                    }

                    const resized = faceapi.resizeResults(
                        detection, {
                            width: video.width,
                            height: video.height,
                        }
                    );

                    faceapi.draw.drawFaceLandmarks(canvas, resized);
                    faceapi.draw.drawDetections(canvas, resized);

                    const distance = faceapi.euclideanDistance(
                        this.referenceDescriptor,
                        detection.descriptor
                    );

                    const matchPercent = this.toMatchPercent(distance);

                    this.distanceText = distance.toFixed(4);
                    this.matchPercentText = `${matchPercent.toFixed(1)}%`;

                    const box = resized.detection.box;

                    ctx.fillStyle = distance <= this.matchThreshold ? 'lime' : 'red';
                    ctx.font = '14px Arial';
                    ctx.fillText(
                        `${matchPercent.toFixed(1)}%`, box.x, Math.max(15, box.y - 5));
                    if (distance <= this.matchThreshold) {
                        this.isMatched = true;
                        this.resultText = `Match ${matchPercent.toFixed(1)}%`;
                        this.stopCamera();
                        return;
                    }
                    this.resultText = `Not Match ${matchPercent.toFixed(1)}%`;
                    requestAnimationFrame(() => this.detectLoop());
                },

                toMatchPercent(distance) {
                    return Math.max(
                        0,
                        Math.min(
                            100,
                            (1 - distance) * 100
                        )
                    );
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

                    console.log('Camera stopped');
                },
            };
        }
    </script>
</body>

</html>
