<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\AIGenerator;
use Illuminate\Http\Request;

class AiChatController extends Controller
{
    protected $ai;

    public function __construct(AIGenerator $ai)
    {
        $this->ai = $ai;
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'history' => 'nullable|array'
        ]);

        // Fetch custom "Nondo" from the database
        $dbFacts = \App\Models\AiKnowledge::active()->orderBy('sort_order')->get();
        $formattedFacts = "";

        foreach($dbFacts as $fact) {
            $formattedFacts .= "- " . ($fact->category ? $fact->category . ": " : "") . $fact->topic . ": " . $fact->content . "\n";
        }

        // Specific instructions for public guest assistant
        $systemPrompt = "You are the Twina Safaris Virtual Assistant, a high-end luxury safari expert.

        KNOWLEDGE BASE (Nondo):
        - Company: Twina Safaris. Known for boutique, private, and highly personalized experiences.
        - Tone: Professional, warm, inviting, and luxury-focused. Use 'Jambo!' for greetings.
        " . $formattedFacts . "
        - Contact: Site WhatsApp (+255 795 482 197), Email (info@twinasafaris.com).

        INSTRUCTIONS:
        1. Answer based on facts above and your general knowledge of Tanzania tourism.
        2. If you don't know something specific about the company, ask them to contact an expert on WhatsApp.
        3. Be brief but thorough.
        4. Remember the chat history to keep context.";

        try {
            $history = $request->history ?? [];
            $response = $this->ai->generateChatResponse($request->message, $history, $systemPrompt);

            return response()->json([
                'response' => $response
            ]);
        } catch (\Exception $e) {
            return response()->json(['response' => "Jambo! Karibu Twina Safaris. For immediate assistance with tours and pricing, please chat with us directly on WhatsApp!"], 500);
        }
    }
}
