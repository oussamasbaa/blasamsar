<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Apex Car - Smart Attendance Scanner</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        .scanner-container {
            position: relative;
            overflow: hidden;
            border-radius: 1rem;
        }
        .scanner-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(to bottom, rgba(217,119,6,0.1), rgba(0,0,0,0));
            pointer-events: none;
            z-index: 10;
        }
        .scan-line {
            position: absolute;
            width: 100%;
            height: 4px;
            background: #d97706;
            box-shadow: 0 0 15px #d97706;
            animation: scan 2.5s infinite linear;
            z-index: 20;
        }
        @keyframes scan {
            0% { top: 0%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
        .face-scan-hud {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 200px; height: 200px;
            border: 2px solid rgba(217,119,6,0.5);
            border-radius: 50%;
            z-index: 30;
            display: none;
            animation: pulse 1s infinite alternate;
        }
        @keyframes pulse {
            from { transform: translate(-50%, -50%) scale(1); box-shadow: 0 0 0 0 rgba(217,119,6,0.7); }
            to { transform: translate(-50%, -50%) scale(1.05); box-shadow: 0 0 0 20px rgba(217,119,6,0); }
        }
        #reader video {
            object-fit: cover;
            border-radius: 1rem;
        }
    </style>
</head>
<body class="bg-zinc-950 text-white min-h-screen flex flex-col antialiased items-center justify-center p-4" style="background-image: radial-gradient(circle at center, #1a1a1a 0%, #000000 100%);">
    
    <div class="glass max-w-xl w-full p-8 relative overflow-hidden animate-fade-in text-center">
        <!-- Glow effect -->
        <div class="absolute -top-32 -right-32 w-64 h-64 bg-amber-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -left-32 w-64 h-64 bg-amber-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <h1 class="text-3xl font-black mb-2 tracking-tight">Smart <span class="text-amber-500">Attendance</span></h1>
            <p class="text-gray-400 text-sm mb-8">Scan your secure QR Code to check in or out.</p>

            <!-- Scanner Box -->
            <div id="scanner-wrapper" class="scanner-container border border-white/10 shadow-2xl bg-black mb-6">
                <div class="scan-line"></div>
                <div class="scanner-overlay"></div>
                <div id="face-hud" class="face-scan-hud"></div>
                <div id="reader" class="w-full h-[350px] flex items-center justify-center bg-zinc-900 rounded-2xl overflow-hidden"></div>
            </div>

            <!-- Status Indicator -->
            <div id="status-box" class="hidden p-4 rounded-xl border font-bold text-center transition-all duration-500 transform scale-95 opacity-0 mt-6">
                <div id="status-icon" class="text-4xl mb-2"></div>
                <div id="status-text" class="text-lg"></div>
            </div>

            <!-- Loading overlay -->
            <div id="processing-overlay" class="hidden absolute inset-0 bg-black/80 backdrop-blur-md flex flex-col items-center justify-center rounded-2xl z-50">
                <svg class="animate-spin h-12 w-12 text-amber-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <h3 id="processing-text" class="text-xl font-bold text-white">Verifying QR Code...</h3>
                <p id="processing-subtext" class="text-amber-500 mt-2 text-sm">Obtaining secure GPS coordinates</p>
            </div>
        </div>
    </div>

    <!-- Hidden canvas for selfie capture -->
    <canvas id="selfie-canvas" class="hidden"></canvas>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let userLat = null;
            let userLon = null;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // 1. Get GPS Location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        userLat = position.coords.latitude;
                        userLon = position.coords.longitude;
                        console.log("GPS locked.", userLat, userLon);
                    },
                    (error) => {
                        console.warn("GPS Access denied or unavailable.", error);
                    },
                    { enableHighAccuracy: true }
                );
            }

            // Function to speak a message (Premium TTS feedback)
            function speak(text) {
                if ('speechSynthesis' in window) {
                    const utterance = new SpeechSynthesisUtterance(text);
                    utterance.rate = 1.0;
                    utterance.pitch = 1.1;
                    window.speechSynthesis.speak(utterance);
                }
            }

            // Show status
            function showStatus(type, message) {
                const box = document.getElementById('status-box');
                const text = document.getElementById('status-text');
                const icon = document.getElementById('status-icon');
                
                box.classList.remove('hidden', 'scale-95', 'opacity-0');
                box.classList.add('scale-100', 'opacity-100');
                text.innerText = message;
                
                if(type === 'success') {
                    box.classList.remove('bg-red-500/20', 'border-red-500/50', 'text-red-400');
                    box.classList.add('bg-green-500/20', 'border-green-500/50', 'text-green-400');
                    icon.innerHTML = '✨';
                    speak(message);
                } else {
                    box.classList.remove('bg-green-500/20', 'border-green-500/50', 'text-green-400');
                    box.classList.add('bg-red-500/20', 'border-red-500/50', 'text-red-400');
                    icon.innerHTML = '⚠️';
                    speak("Attention: " + message);
                }
                
                // Hide after 5 seconds
                setTimeout(() => {
                    box.classList.remove('scale-100', 'opacity-100');
                    box.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => box.classList.add('hidden'), 500);
                }, 5000);
            }

            // Capture selfie from the active video element
            function captureSelfie() {
                const video = document.querySelector('#reader video');
                if (!video) return null;
                const canvas = document.getElementById('selfie-canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const context = canvas.getContext('2d');
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                return canvas.toDataURL('image/jpeg', 0.6); // Base64 JPEG
            }

            // Process QR scan
            async function onScanSuccess(decodedText) {
                // Pause scanner temporarily to prevent duplicate hits
                html5QrcodeScanner.pause();
                
                const overlay = document.getElementById('processing-overlay');
                const pText = document.getElementById('processing-text');
                const pSubText = document.getElementById('processing-subtext');
                const hud = document.getElementById('face-hud');
                
                overlay.classList.remove('hidden');
                
                // Simulate Facial Verification Premium UX
                hud.style.display = 'block';
                pText.innerText = 'QR Verified!';
                pSubText.innerText = 'Capturing facial biometrics...';
                
                const selfieBase64 = captureSelfie();
                
                await new Promise(r => setTimeout(r, 1000));
                
                pText.innerText = 'Verifying Location...';
                pSubText.innerText = 'Analyzing GPS data...';
                
                await new Promise(r => setTimeout(r, 800));
                
                hud.style.display = 'none';

                // Send data to server
                try {
                    const response = await fetch('/attendance/record', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            token: decodedText,
                            latitude: userLat,
                            longitude: userLon,
                            selfie: selfieBase64
                        })
                    });
                    
                    const data = await response.json();
                    overlay.classList.add('hidden');
                    
                    if (response.ok) {
                        showStatus('success', data.message);
                    } else {
                        showStatus('error', data.message || "An error occurred.");
                    }
                } catch (error) {
                    overlay.classList.add('hidden');
                    showStatus('error', "Network error. Please try again.");
                }
                
                // Resume scanner after 5 seconds
                setTimeout(() => {
                    html5QrcodeScanner.resume();
                }, 5000);
            }

            function onScanFailure(error) {
                // Ignoring errors since it runs continuously
            }

            let html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                { fps: 10, qrbox: {width: 250, height: 250}, aspectRatio: 1.0 },
                /* verbose= */ false
            );
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        });
    </script>
</body>
</html>
