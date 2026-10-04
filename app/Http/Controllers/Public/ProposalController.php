<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\AdminNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProposalController extends Controller
{
    public function show($token)
    {
        $proposal = Proposal::where('token', $token)->firstOrFail();

        // Increment view count and timestamps
        $isFirstView = is_null($proposal->first_viewed_at) && is_null($proposal->viewed_at);

        if ($isFirstView) {
            $proposal->first_viewed_at = now();
        }

        $proposal->last_viewed_at = now();
        $proposal->viewed_at = now();
        $proposal->view_count = ($proposal->view_count ?? 0) + 1;

        if ($proposal->status === Proposal::STATUS_SENT) {
            $proposal->status = Proposal::STATUS_VIEWED;
        }

        $proposal->save();

        if ($isFirstView) {
            AdminNotification::create([
                'type' => 'proposal',
                'title' => 'Proposal Viewed: ' . $proposal->client_name,
                'message' => "Client {$proposal->client_name} has viewed their proposal '{$proposal->title}' for the first time.",
                'link' => route('admin.proposals.show', $proposal),
                'is_read' => false,
            ]);
        }

        return view('public.proposals.show', compact('proposal'));
    }

    public function accept(Request $request, $token)
    {
        $proposal = Proposal::where('token', $token)->firstOrFail();

        $validated = $request->validate([
            'signature_name' => 'required|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_notes' => 'nullable|string|max:2000',
        ]);

        $feedbackNote = "Accepted by: " . $validated['signature_name'];
        if (!empty($validated['client_notes'])) {
            $feedbackNote .= "\nClient Notes: " . $validated['client_notes'];
        }

        $proposal->update([
            'status' => Proposal::STATUS_ACCEPTED,
            'accepted_at' => now(),
            'signature_name' => $validated['signature_name'],
            'accepted_email' => $validated['client_email'] ?? $proposal->client_email,
            'client_feedback' => $feedbackNote,
        ]);

        AdminNotification::create([
            'type' => 'proposal_accepted',
            'title' => 'Proposal Accepted: ' . $proposal->client_name,
            'message' => "Client {$proposal->client_name} ACCEPTED proposal '{$proposal->title}' ({$proposal->full_reference})!",
            'link' => route('admin.proposals.show', $proposal),
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Thank you! You have successfully accepted this proposal. Our team will contact you shortly to finalize your safari.');
    }

    public function requestChanges(Request $request, $token)
    {
        $proposal = Proposal::where('token', $token)->firstOrFail();

        $validated = $request->validate([
            'feedback' => 'required|string|min:5|max:2000',
        ]);

        $proposal->update([
            'status' => Proposal::STATUS_CHANGES_REQUESTED,
            'client_feedback' => $validated['feedback'],
        ]);

        AdminNotification::create([
            'type' => 'proposal_changes',
            'title' => 'Changes Requested: ' . $proposal->client_name,
            'message' => "Client {$proposal->client_name} requested changes for proposal '{$proposal->title}'",
            'link' => route('admin.proposals.show', $proposal),
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Your request for changes has been submitted! Our travel consultants will update your itinerary and reach out.');
    }

    public function downloadPdf($token)
    {
        $proposal = Proposal::where('token', $token)->firstOrFail();

        $pdf = Pdf::loadView('proposals.pdf', compact('proposal'))
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);
        $filename = 'Safari-Proposal-' . Str::slug($proposal->client_name) . '.pdf';

        return $pdf->download($filename);
    }
}
