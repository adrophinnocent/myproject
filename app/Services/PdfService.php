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

        // Ensure the directory exists
        if (!Storage::disk('public')->exists('temp')) {
            Storage::disk('public')->makeDirectory('temp');
        }

        Storage::disk('public')->put($path, $pdf->output());

        return storage_path('app/public/' . $path);
    }
}
