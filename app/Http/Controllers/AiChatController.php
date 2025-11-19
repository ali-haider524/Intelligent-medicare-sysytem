<?php

namespace App\Http\Controllers;

use App\Services\AiChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AiChatController extends Controller
{
    private $aiChatService;

    public function __construct(AiChatService $aiChatService)
    {
        $this->aiChatService = $aiChatService;
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'session_id' => 'nullable|string',
        ]);

        $sessionId = $request->session_id ?? 'chat_' . Str::random(16);
        $userId = auth()->id();

        $response = $this->aiChatService->processMessage(
            $sessionId,
            $request->message,
            $userId
        );

        return response()->json($response);
    }

    public function startSession(Request $request)
    {
        $sessionId = 'chat_' . Str::random(16);
        
        return response()->json([
            'session_id' => $sessionId,
            'message' => 'Hello! I\'m your AI medical assistant. How can I help you today?'
        ]);
    }

    public function getChatHistory(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
        ]);

        $session = \App\Models\AiChatSession::where('session_id', $request->session_id)->first();

        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        return response()->json([
            'conversation_history' => $session->conversation_history,
            'chat_type' => $session->chat_type,
            'extracted_symptoms' => $session->extracted_symptoms,
            'ai_recommendations' => $session->ai_recommendations,
        ]);
    }
}