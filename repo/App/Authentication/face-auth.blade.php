<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Face Recognition Test</title>
       <script defer src= 'https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js'></script>
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
            <img id="referenceImage" width="300">
        </div>
        <div>
            <h4>Webcam</h4>
            <video id="video" width="300" height="225" autoplay muted playsinline></video>
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
                    const distance = faceapi.euclideanDistance(referenceDescriptor, detection.descriptor);

                    resultText.innerText = `${distance < 0.5 ? '✅ Match' : '❌ Not Match'} | Distance: ${distance.toFixed(4)}`;

                    // stop when strong match
                    if (distance <= 0.5) {
                        resultText.innerText += ' ✅ STOPPED';
                        stopCamera(video);
                        return;
                    }

                } else {
                    resultText.innerText = 'No face';
                }

                requestAnimationFrame(detectLoop);
            }

            video.onloadeddata = () => detectLoop();

        });
    </script>

</body>

</html>