<div class="flex flex-col items-center justify-center p-4">
    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($token) }}" alt="QR Code" class="rounded-lg shadow-sm border border-gray-200 p-2">
    <p class="mt-4 text-sm text-gray-500 font-mono">Token: {{ $token }}</p>
</div>
