<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ChatbotService
{
    public function askQuestion($question)
    {
        // Ambil API key dari .env
        $apiKey = config('services.huggingface.key');
        
        // URL untuk mengakses API Hugging Face dengan model tertentu
        $url = 'https://api-inference.huggingface.co/models/gpt2'; // Model GPT2 atau lainnya yang ada

        // Mengirim permintaan ke API Hugging Face
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
        ])->post($url, [
            'inputs' => $question,  // Pertanyaan pengguna
        ]);

        // Mengembalikan jawaban dari API Hugging Face
        return $response->json()['generated_text'] ?? 'Maaf saya tidak mengerti pertanyaan Anda.';
    }
}
