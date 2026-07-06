<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    public function generateItinerary($booking)
    {
        $fileName = 'itinerary-' . $booking->booking_reference . '.pdf';
        $path = 'temp/' . $fileName;

        $pdf = Pdf::loadView('emails.itinerary-pdf', compact('booking'));

        // Use standard filesystem to avoid issues with custom disks on shared hosting
        $storageDir = storage_path('app/public/temp');
        if (!file_exists($storageDir)) {
            mkdir($storageDir, 0755, true);
        }

        $fullPath = $storageDir . '/' . $fileName;
        file_put_contents($fullPath, $pdf->output());

        return $fullPath;
    }

    public function generateInvoice($booking)
    {
        $fileName = 'invoice-' . $booking->booking_reference . '.pdf';

        $pdf = Pdf::loadView('emails.invoice-pdf', compact('booking'));

        $storageDir = storage_path('app/public/temp');
        if (!file_exists($storageDir)) {
            mkdir($storageDir, 0755, true);
        }

        $fullPath = $storageDir . '/' . $fileName;
        file_put_contents($fullPath, $pdf->output());

        return $fullPath;
    }
}
