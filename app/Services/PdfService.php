<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\TripPlan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class PdfService
{
    /**
     * Generated PDFs contain customer PII and must never be written to the
     * public disk. They are created in storage/app/private/temp and are
     * deleted by the caller after the email/download completes.
     */
    public function generateItinerary($model)
    {
        $fileName = 'itinerary-'.Str::random(32).'.pdf';

        $pdf = Pdf::loadView('emails.itinerary-pdf', [
            'booking' => $model instanceof Booking ? $model : null,
            'tripPlan' => $model instanceof TripPlan ? $model : null,
        ]);

        return $this->writePrivate($fileName, $pdf->output());
    }

    public function generateInvoice($booking)
    {
        $fileName = 'invoice-'.Str::random(32).'.pdf';

        $pdf = Pdf::loadView('emails.invoice-pdf', compact('booking'));

        return $this->writePrivate($fileName, $pdf->output());
    }

    private function writePrivate(string $fileName, string $contents): string
    {
        $storageDir = storage_path('app/private/temp');

        if (! is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }

        $fullPath = $storageDir.DIRECTORY_SEPARATOR.$fileName;
        file_put_contents($fullPath, $contents);

        return $fullPath;
    }
}
