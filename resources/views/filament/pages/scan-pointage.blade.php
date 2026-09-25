<x-filament-panels::page>
    <div class="space-y-6">
        @if ($message)
            <div class="p-4 rounded-lg {{ $status === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $message }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-xl shadow-sm dark:bg-gray-800">
            <h2 class="text-lg font-bold mb-4 dark:text-white">Scan Worker QR Code</h2>
            <div id="reader" width="600px"></div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        document.addEventListener('livewire:load', function () {
            function onScanSuccess(decodedText, decodedResult) {
                // handle the scanned code as you like, for example:
                console.log(`Code matched = ${decodedText}`, decodedResult);
                
                // Call livewire method
                @this.call('processQr', decodedText);
                
                // Stop scanning temporarily
                html5QrcodeScanner.clear();
                
                // Restart after 3 seconds
                setTimeout(() => {
                    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                }, 3000);
            }

            function onScanFailure(error) {
                // handle scan failure, usually better to ignore and keep scanning.
            }

            let html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                { fps: 10, qrbox: {width: 250, height: 250} },
                /* verbose= */ false);
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        });
        
        // Filament 3 runs Livewire slightly differently, we can just run on DOMContentLoaded
        document.addEventListener('DOMContentLoaded', function () {
            if(typeof Html5QrcodeScanner !== 'undefined') {
                function onScanSuccess(decodedText, decodedResult) {
                    @this.call('processQr', decodedText);
                    html5QrcodeScanner.clear();
                    setTimeout(() => {
                        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                    }, 3000);
                }
                function onScanFailure(error) { }
                let html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: {width: 250, height: 250} }, false);
                html5QrcodeScanner.render(onScanSuccess, onScanFailure);
            }
        });
    </script>
</x-filament-panels::page>
