<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AIService
{
    public function generate($prompt)
    { dd([
            'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
            'Content-Type' => 'application/json',
        ]);
        $response = Http::retry(3, 2000) // 3 retry, 2 sec gap
            ->withHeaders([
            'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'llama-3.3-70b-versatile', // best model
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ]
        ]);        
        return $response['choices'][0]['message']['content'] ?? '';
    }
}
