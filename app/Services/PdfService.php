<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    public function generateItinerary($model)
    {
        $reference = $model instanceof \App\Models\Booking ? $model->booking_reference : 'TP-' . $model->id;
        $fileName = 'itinerary-' . $reference . '.pdf';

        $pdf = Pdf::loadView('emails.itinerary-pdf', [
            'booking' => $model instanceof \App\Models\Booking ? $model : null,
            'tripPlan' => $model instanceof \App\Models\TripPlan ? $model : null
        ]);

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
