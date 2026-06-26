import * as faceapi from "face-api.js";

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

        resultText.innerHTML = matched
            ? `✅ Match (${distance.toFixed(4)})`
            : `❌ Not Match (${distance.toFixed(4)})`;

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
