<?php

namespace App\Http\Controllers\Admin;

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

    public function index()
    {
        return view('admin.ai-chat.index');
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'history' => 'nullable|array'
        ]);

        $systemPrompt = "You are the Twina Safaris Business Partner & Senior Consultant.
        Your mission is to help Bella (the admin) scale safari operations and manage the business effectively.

        CORE CAPABILITIES:
        1. DRAFTING: Write engaging safari blog posts and marketing copy.
        2. CUSTOMER CARE: Draft professional, empathetic, and persuasive replies to complex customer inquiries.
        3. LOGISTICS: Explain Tanzania park regulations (TANAPA/NCAA), permit fees, and tour logistics.
        4. STRATEGY: Suggest ways to optimize tours and increase bookings.

        TONE:
        - Executive, supportive, and highly knowledgeable.
        - Start conversations with 'Jambo!'.
        - Refer to the company as 'Twina Safaris' and 'our business'.";

        $response = $this->ai->generateChatResponse($request->message, $request->history ?? [], $systemPrompt);

        return response()->json([
            'response' => $response
        ]);
    }
}
