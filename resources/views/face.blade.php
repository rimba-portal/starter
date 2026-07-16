<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Face Recognition Test</title>
    <script defer src='https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js'></script>
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

<body>

    <h2>Face Match (Realtime)</h2>

    <input type="text" id="filenameInput" placeholder="Enter filename (e.g. 123456)">
    <button onclick="loadImage()">Load Image</button>

    <div class="container">
        <div>
            <h4>Reference Image</h4>
            <img id="referenceImage" height="250">
        </div>
        <div>
            <h4>Webcam</h4>

            <div style="position: relative; display: inline-block;">
                <video id="video" width="300" height="250" autoplay muted playsinline>
                </video>

                <canvas id="overlay" width="300" height="250"
                    style=" position:absolute; top:0; left:0; pointer-events:none; ">
                </canvas>
            </div>
        </div>
    </div>

    <h3 id="result"></h3>

    <script>
        // ✅ Global state
        let referenceImage;
        let resultText;
        let referenceDescriptor = null;
        let videoStream = null;

        // ✅ Load image from filename
        window.loadImage = async function() {
            const filename = document.getElementById('filenameInput').value.trim();
            if (!filename) return alert('Enter filename');

            referenceImage.src = `/pic/${filename}`;

            referenceImage.onload = async () => {
                const detection = await faceapi
                    .detectSingleFace(referenceImage, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (!detection) return alert('No face detected');

                referenceDescriptor = detection.descriptor;
                resultText.innerText = 'Reference loaded ✅';
            };

            referenceImage.onerror = () => {
                resultText.innerText = '❌ Failed to load image';
            };
        };

        // ✅ Stop camera
        function stopCamera(video) {
            if (videoStream) {
                videoStream.getTracks().forEach(t => t.stop());
                video.pause();
                console.log('🛑 Camera stopped');
            }
        }

        // ✅ Main app
        window.addEventListener('load', async () => {

            const video = document.getElementById('video');
            const canvas = document.getElementById('overlay');
            const ctx = canvas.getContext('2d');

            resultText = document.getElementById('result');
            referenceImage = document.getElementById('referenceImage');

            // load models
            const MODEL_URL = '/models';
            await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
            await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
            await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);

            // start camera
            videoStream = await navigator.mediaDevices.getUserMedia({
                video: true
            });
            video.srcObject = videoStream;
            console.log('✅ Camera started');

            // detection loop
            async function detectLoop() {
                if (!referenceDescriptor) return requestAnimationFrame(detectLoop);

                const detection = await faceapi
                    .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (detection) {

                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    const resized = faceapi.resizeResults(
                        detection, {
                            width: video.width,
                            height: video.height,
                        }
                    );

                    // Draw face box
                    // faceapi.draw.drawDetections(canvas, resized);

                    // Draw landmarks
                    faceapi.draw.drawFaceLandmarks(canvas, resized);

                    const distance = faceapi.euclideanDistance(
                        referenceDescriptor,
                        detection.descriptor
                    );

                    const box = resized.detection.box;

                    ctx.fillStyle = 'lime';
                    ctx.font = '14px Arial';

                    ctx.fillText(
                        `D=${distance.toFixed(3)}`,
                        box.x,
                        Math.max(15, box.y - 5)
                    );

                    resultText.innerText =
                        `${distance < 0.45 ? '✅ Match' : '❌ Not Match'} | Distance: ${distance.toFixed(4)}`;

                    if (distance <= 0.45) {
                        resultText.innerText += ' ✅ STOPPED';
                        stopCamera(video);
                        return;
                    }

                } else {

                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    resultText.innerText = 'No face';
                }
                requestAnimationFrame(detectLoop);
            }

            video.onloadeddata = () => detectLoop();

        });
    </script>

</body>

</html>
