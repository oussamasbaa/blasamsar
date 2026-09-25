<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ScanPointage extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-qr-code';
    protected string $view = 'filament.pages.scan-pointage';
    public ?string $message = null;
    public ?string $status = null;

    public function processQr($token)
    {
        $worker = \App\Models\Worker::where('qr_token', $token)->first();

        if (!$worker) {
            $this->status = 'error';
            $this->message = "Invalid QR Code!";
            return;
        }

        // Get the last pointage
        $lastPointage = $worker->pointages()->latest('recorded_at')->first();
        $type = 'entry';

        if ($lastPointage && $lastPointage->type === 'entry') {
            $type = 'exit';
        }

        $worker->pointages()->create([
            'type' => $type,
            'recorded_at' => now(),
        ]);

        $this->status = 'success';
        $this->message = "{$worker->name} has " . ($type === 'entry' ? 'clocked in.' : 'clocked out.');
    }
}
