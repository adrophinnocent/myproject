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

        $response = $this->ai->generateChatResponse($request->message, $request->history ?? []);

        return response()->json([
            'response' => $response
        ]);
    }
}
