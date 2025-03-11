<?php

namespace App\Http\Controllers;

use App\Services\ChatbotService;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    protected $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    public function index () {
        return view('chatbox.index');
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        // Mengirim pesan pengguna ke ChatbotService
        $response = $this->chatbotService->askQuestion($request->message);

        return response()->json([
            'response' => $response
        ]);
    }

    public function ask(Request $request)
    {
        $question = $request->input('question');
        $answer = $this->chatbotService->askQuestion($question);

        return response()->json(['answer' => $answer]);
    }
}
