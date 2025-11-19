<?php

class AIService
{
    private string $openaiApiKey;

    public function __construct(string $apiKey)
    {
        $this->openaiApiKey = $apiKey;
    }

    // Parse free-text input into structured JSON
    public function parseFreeText(string $freeText): array
    {
        $prompt = "Parse the following free-text health log into structured JSON. Example: 'walked 25 min, 2 coffees, slept at 01:30' → {\"exercise\": {\"type\": \"walking\", \"duration\": 25}, \"caffeine\": 2, \"sleep\": \"01:30\"}. Input: $freeText";

        $response = $this->callOpenAI($prompt);

        if ($response && isset($response['choices'][0]['text'])) {
            return json_decode($response['choices'][0]['text'], true);
        }

        return ['error' => 'Failed to parse free text'];
    }

    // Generate a weekly summary for the user
    public function generateWeeklySummary(array $weeklyData): string
    {
        $prompt = "Generate a weekly health summary based on the following data: " . json_encode($weeklyData);

        $response = $this->callOpenAI($prompt);

        if ($response && isset($response['choices'][0]['text'])) {
            return trim($response['choices'][0]['text']);
        }

        return "Failed to generate weekly summary.";
    }

    // Provide AI-powered suggestions (e.g., nutrition coach)
    public function getSuggestions(array $userData): string
    {
        $prompt = "Based on the following user data, provide personalized health suggestions: " . json_encode($userData);

        $response = $this->callOpenAI($prompt);

        if ($response && isset($response['choices'][0]['text'])) {
            return trim($response['choices'][0]['text']);
        }

        return "Failed to generate suggestions.";
    }

    // Call OpenAI API
    private function callOpenAI(string $prompt): ?array
    {
        $url = "https://api.openai.com/v1/completions";

        $data = [
            "model" => "text-davinci-003",
            "prompt" => $prompt,
            "max_tokens" => 150,
            "temperature" => 0.7
        ];

        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->openaiApiKey
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            return json_decode($result, true);
        }

        return null;
    }
}
?>