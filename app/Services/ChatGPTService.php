<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ChatGPTService
{
    protected $apiKey;

    public function __construct()
    {
        //$this->apiKey = env('OPENAI_API_KEY'); // Ensure this is set in your .env file
        $this->apiKey = "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";
    }

    public function getDiagnosis(string $description, ?string $imagePath = null)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a skin diagnosis assistant.'],
                ['role' => 'user', 'content' => $description],
            ],
            'temperature' => 0.7,
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to communicate with OpenAI API: ' . $response->body());
        }


        // Get the response content (text from ChatGPT)
        $content = $response->json()['choices'][0]['message']['content'];

        return $content;
    }

    private function parseContentIntoNodes(string $content): array
    {

        $nodes = [];
        $currentHeading = null;
        $currentBody = '';

        // Split content into lines
        $lines = explode("\n", $content);

        

        foreach ($lines as $line) {
            // Trim any extra spaces from the line
            $line = trim($line);

            // Check if the line is a heading (starts with #)
            if (preg_match('/^#+\s*/', $line)) {
                // If there was a previous heading and body, add it as a node
                if ($currentHeading && $currentBody) {
                    $nodes[] = [
                        'heading' => $currentHeading,
                        'body' => trim($currentBody),
                    ];
                }

                // Start a new node for the current heading
                $currentHeading = trim(preg_replace('/^#+\s*/', '', $line)); // Remove the Markdown '#' characters
                $currentBody = ''; // Reset body for the new heading
            } elseif (!empty($line)) {
                // Otherwise, append the line to the current body (if it's not empty)
                $currentBody .= $line . "\n"; // Preserve line breaks for body
            }
        }

        // Add the last node if any
        if ($currentHeading && $currentBody) {
            $nodes[] = [
                'heading' => $currentHeading,
                'body' => trim($currentBody),
            ];
        }

        // Dump the nodes for debugging
        dd($nodes);

        return $nodes;
    }
}
