<?php

namespace App\Services\AI;

use ImranDevBd\AiHub\Facades\AIHub;

class BusinessAssistantService
{
    /**
     * @param  array<string, mixed>  $context
     * @return array{answer: string, requires_human_review: bool, suggestions: list<string>}
     */
    public function advise(string $prompt, array $context = []): array
    {
        $system = <<<'SYS'
You assist buying-house operations staff. Never instruct auto-approval of purchase orders, allocations, or payments.
Always set requires_human_review to true when the user asks for approvals or irreversible actions.
SYS;

        $response = AIHub::system($system)
            ->prompt($this->formatPrompt($prompt, $context))
            ->send();

        $text = $response->content;

        return [
            'answer' => trim($text),
            'requires_human_review' => true,
            'suggestions' => $this->extractSuggestions($text),
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     */
    protected function formatPrompt(string $prompt, array $context): string
    {
        if ($context === []) {
            return $prompt;
        }

        return $prompt."\n\nContext:\n".json_encode($context, JSON_PRETTY_PRINT);
    }

    /**
     * @return list<string>
     */
    protected function extractSuggestions(string $text): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $text) ?: [];
        $suggestions = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (str_starts_with($line, '- ') || str_starts_with($line, '* ')) {
                $suggestions[] = ltrim($line, '-* ');
            }
        }

        return $suggestions;
    }
}
