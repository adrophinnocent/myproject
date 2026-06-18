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

        // Specific instructions for public guest assistant
        $systemPrompt = "You are the Twina Safaris Virtual Assistant. You help international travelers plan their dream safari in Tanzania. You are expert in Serengeti, Kilimanjaro, Zanzibar, and Ngorongoro. Keep responses professional, luxury-focused, and inviting. Help them choose tours and answer logistical questions.";

        try {
            $history = $request->history ?? [];
            $response = $this->ai->generateChatResponse($request->message, $history);

            return response()->json([
                'response' => $response
            ]);
        } catch (\Exception $e) {
            return response()->json(['response' => "Jambo! I'm having a little trouble connecting. Please try again or message us on WhatsApp!"], 500);
        }
    }
}
