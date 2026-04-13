<?php

namespace App\Livewire;

use App\Models\Scan;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Native\Mobile\Attributes\OnNative;
use Native\Mobile\Events\Scanner\CodeScanned;
use Native\Mobile\Events\Scanner\ScannerCancelled;
use Native\Mobile\Facades\Scanner;

#[Layout('components.layouts.app')]
class QrScanner extends Component
{
    public string $status = '';

    public function startScan(): void
    {
        $this->status = 'Opening scanner...';
        try {
            Scanner::scan()
                ->prompt('Point camera at a QR or barcode')
                ->formats(['all'])
                ->scan();
        } catch (\Throwable $e) {
            $this->status = 'Error: ' . $e->getMessage();
            Log::error('Scanner failed: ' . $e->getMessage());
        }
    }

    #[OnNative(CodeScanned::class)]
    public function handleCodeScanned(string $data, string $format): void
    {
        Log::info('CodeScanned: ' . $data . ' format: ' . $format);

        Scan::create([
            'data'   => $data,
            'format' => $format,
            'type'   => Scan::detectType($data),
        ]);

        $this->status = 'Scanned: ' . $format;
    }

    #[OnNative(ScannerCancelled::class)]
    public function handleScannerCancelled(): void
    {
        $this->status = '';
    }

    public function deleteScan(int $id): void
    {
        Scan::findOrFail($id)->delete();
    }

    public function clearAll(): void
    {
        Scan::truncate();
        $this->status = '';
    }

    public function render()
    {
        return view('livewire.qr-scanner', [
            'scans' => Scan::latest()->get(),
        ]);
    }
}