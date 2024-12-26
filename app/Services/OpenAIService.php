<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key'); // Load from the config file
    }

    /**
     * Get specialization suggestion from OpenAI.
     *
     * @param string $problemDescription
     * @return string
     */
    public function getSpecialization(string $problemDescription): string
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post('https://api.openai.com/v1/completions', [
            'model' => 'text-davinci-003', // Example OpenAI model
            'prompt' => "Suggest a medical specialization for the following problem: $problemDescription",
            'max_tokens' => 100,
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to communicate with OpenAI API');
        }

        $data = $response->json();

        return $data['choices'][0]['text'] ?? 'No suggestion available';
    }
}
