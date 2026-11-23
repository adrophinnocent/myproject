<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AIGenerator
{
    protected $apiKey;

    protected $baseUrl = 'https://api.openai.com/v1';

    public function __construct()
    {
        $this->apiKey = config('services.openai.key');
    }

    public function generateImageContent(string $imagePath, string $category): array
    {
        if (empty($this->apiKey)) {
            return $this->getFallbackContent($category);
        }

        try {
            $disk = Storage::disk('public');

            if (! $disk->exists($imagePath)) {
                Log::warning('AI image content skipped: file not found at '.$imagePath);

                return $this->getFallbackContent($category);
            }

            $mime = $disk->mimeType($imagePath) ?: 'image/jpeg';
            $dataUrl = 'data:'.$mime.';base64,'.base64_encode($disk->get($imagePath));

            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post($this->baseUrl.'/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => $this->getSystemPrompt($category)],
                    [
                        'role' => 'user',
                        'content' => [
                            ['type' => 'text', 'text' => $this->getUserPrompt($category)],
                            ['type' => 'image_url', 'image_url' => ['url' => $dataUrl]],
                        ],
                    ],
                ],
                'max_tokens' => 500,
            ]);

            if ($response->successful()) {
                $content = $response->json()['choices'][0]['message']['content'] ?? '';

                if (is_string($content) && trim($content) !== '') {
                    return $this->parseGeneratedContent($content);
                }
            }

            Log::error('AI image content generation failed: '.$response->body());
        } catch (\Throwable $e) {
            Log::error('AI image content generation exception: '.$e->getMessage());
        }

        return $this->getFallbackContent($category);
    }

    public function generateSeoMetadata(string $title, string $description): array
    {
        if (empty($this->apiKey)) {
            return [];
        }

        try {
            $prompt = "You are a luxury travel SEO expert. Generate SEO metadata for a safari tour in Tanzania.\n\n".
                "Tour Title: {$title}\n".
                "Tour Description: {$description}\n\n".
                "Generate:\n".
                "1. Meta Title (max 60 chars, compelling, includes keywords)\n".
                "2. Meta Description (max 160 chars, inviting, summary of experience)\n".
                "3. Meta Keywords (5-8 comma-separated terms)\n\n".
                "Format your response EXACTLY as follows:\n".
                "TITLE: [your title]\n".
                "DESCRIPTION: [your description]\n".
                'KEYWORDS: [comma separated keywords]';

            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post($this->baseUrl.'/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a professional SEO expert for luxury tourism.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 300,
            ]);

            if ($response->successful()) {
                $content = $response->json()['choices'][0]['message']['content'];

                return $this->parseSeoContent($content);
            }

            return [];
        } catch (\Exception $e) {
            Log::error('AI SEO generation failed: '.$e->getMessage());

            return [];
        }
    }

    protected function parseSeoContent(string $content): array
    {
        $lines = explode("\n", trim($content));
        $data = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (str_starts_with($line, 'TITLE:')) {
                $data['meta_title'] = trim(str_replace('TITLE:', '', $line));
            } elseif (str_starts_with($line, 'DESCRIPTION:')) {
                $data['meta_description'] = trim(str_replace('DESCRIPTION:', '', $line));
            } elseif (str_starts_with($line, 'KEYWORDS:')) {
                $data['meta_keywords'] = trim(str_replace('KEYWORDS:', '', $line));
            }
        }

        return $data;
    }

    public function generateChatResponse(string $message, array $history = [], ?string $systemPrompt = null): string
    {
        if (empty($this->apiKey)) {
            return $this->chatFallback();
        }

        try {
            $messages = [
                ['role' => 'system', 'content' => $systemPrompt ?? 'You are the Twina Safaris AI Assistant. You help international travelers plan their dream safari in Tanzania. You are expert in Serengeti, Kilimanjaro, Zanzibar, and Ngorongoro. Keep responses professional, luxury-focused, and inviting.'],
            ];

            // Only keep a short, sanitized window of history. The browser can
            // send arbitrary JSON, so roles and lengths must be constrained.
            foreach (array_slice($history, -10) as $chat) {
                if (! is_array($chat)) {
                    continue;
                }

                if (isset($chat['role'], $chat['content']) && is_string($chat['content'])) {
                    $role = in_array($chat['role'], ['user', 'assistant'], true) ? $chat['role'] : 'user';
                    $messages[] = ['role' => $role, 'content' => mb_substr($chat['content'], 0, 2000)];
                } elseif (isset($chat['from'], $chat['text']) && is_string($chat['text'])) {
                    $role = ($chat['from'] === 'user') ? 'user' : 'assistant';
                    $messages[] = ['role' => $role, 'content' => mb_substr($chat['text'], 0, 2000)];
                }
            }

            $messages[] = ['role' => 'user', 'content' => mb_substr($message, 0, 2000)];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(45)->post($this->baseUrl.'/chat/completions', [
                'model' => 'gpt-4o-mini', // Using mini for faster/cheaper responses
                'messages' => $messages,
                'max_tokens' => 1000,
            ]);

            if ($response->successful()) {
                return (string) $response->json()['choices'][0]['message']['content'];
            }

            Log::error('AI Chat API Error: '.$response->body());
        } catch (\Throwable $e) {
            Log::error('AI Chat failed: '.$e->getMessage());
        }

        return $this->chatFallback();
    }

    private function chatFallback(): string
    {
        return 'Jambo! Karibu Twina Safaris. For the fastest response regarding our tours, prices, and bookings, please chat with our expert guides directly on WhatsApp!';
    }

    public function generateSearchAnswerAndSeo(string $questionText, string $categoryName, ?string $tourContext = null): array
    {
        if (empty($this->apiKey)) {
            return [];
        }

        try {
            $prompt = "You are the head SEO and Travel Intelligence Specialist for Twina Safaris (a premier Tanzanian safari operator).\n".
                "A tourist asked the following question on AI/Search platforms (Google, ChatGPT, Gemini, Perplexity):\n\n".
                "QUESTION: {$questionText}\n".
                "CATEGORY: {$categoryName}\n".
                ($tourContext ? "LINKED TOUR PACKAGE: {$tourContext}\n" : '')."\n".
                "Task:\n".
                "1. Provide a comprehensive, luxury-focused, factual, and persuasive answer (150-250 words) that positions Twina Safaris as the top expert operator. Do not invent fake prices or awards.\n".
                "2. Provide an SEO Meta Title (max 60 chars).\n".
                "3. Provide a Meta Description (max 155 chars).\n".
                "4. Identify the Primary Keyword.\n".
                "5. Identify 4-6 Secondary Keywords (comma-separated).\n".
                "6. Classify Search Intent (one of: Informational, Commercial, Transactional, Navigational).\n".
                "7. List 3 Related Questions tourists also ask.\n\n".
                "Format your response EXACTLY as follows:\n".
                "META_TITLE: [title]\n".
                "META_DESCRIPTION: [description]\n".
                "PRIMARY_KEYWORD: [primary keyword]\n".
                "SECONDARY_KEYWORDS: [kw1, kw2, kw3, kw4]\n".
                "SEARCH_INTENT: [Informational/Commercial/Transactional/Navigational]\n".
                "RELATED_QUESTIONS: [Q1 | Q2 | Q3]\n".
                "ANSWER:\n".
                '[Comprehensive answer text here]';

            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(90)->post($this->baseUrl.'/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an expert AI Travel Search Intelligence Strategist for Twina Safaris Tanzania.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 1200,
            ]);

            if ($response->successful()) {
                $rawContent = $response->json()['choices'][0]['message']['content'];

                return $this->parseSearchAnswerAndSeo($rawContent);
            }

            Log::error('AI Search Answer Generation failed: '.$response->body());

            return [];
        } catch (\Exception $e) {
            Log::error('AI Search Answer Exception: '.$e->getMessage());

            return [];
        }
    }

    protected function parseSearchAnswerAndSeo(string $rawContent): array
    {
        $data = [
            'meta_title' => '',
            'meta_description' => '',
            'primary_keyword' => '',
            'secondary_keywords' => [],
            'search_intent' => 'informational',
            'related_questions' => [],
            'content' => '',
        ];

        if (str_contains($rawContent, 'ANSWER:')) {
            $parts = explode('ANSWER:', $rawContent, 2);
            $headerPart = $parts[0];
            $data['content'] = trim($parts[1]);
        } else {
            $headerPart = $rawContent;
            $data['content'] = trim($rawContent);
        }

        $lines = explode("\n", trim($headerPart));
        foreach ($lines as $line) {
            $line = trim($line);
            if (str_starts_with($line, 'META_TITLE:')) {
                $data['meta_title'] = trim(str_replace('META_TITLE:', '', $line));
            } elseif (str_starts_with($line, 'META_DESCRIPTION:')) {
                $data['meta_description'] = trim(str_replace('META_DESCRIPTION:', '', $line));
            } elseif (str_starts_with($line, 'PRIMARY_KEYWORD:')) {
                $data['primary_keyword'] = trim(str_replace('PRIMARY_KEYWORD:', '', $line));
            } elseif (str_starts_with($line, 'SECONDARY_KEYWORDS:')) {
                $kws = trim(str_replace('SECONDARY_KEYWORDS:', '', $line));
                $data['secondary_keywords'] = array_map('trim', explode(',', $kws));
            } elseif (str_starts_with($line, 'SEARCH_INTENT:')) {
                $intent = strtolower(trim(str_replace('SEARCH_INTENT:', '', $line)));
                if (in_array($intent, ['informational', 'commercial', 'transactional', 'navigational'])) {
                    $data['search_intent'] = $intent;
                }
            } elseif (str_starts_with($line, 'RELATED_QUESTIONS:')) {
                $rqs = trim(str_replace('RELATED_QUESTIONS:', '', $line));
                $data['related_questions'] = array_map('trim', explode('|', $rqs));
            }
        }

        return $data;
    }

    protected function getSystemPrompt(string $category): string
    {
        $prompts = [
            'destination' => 'You are a travel and tourism expert specializing in destination marketing. Generate SEO-friendly content for travel images.',
            'tour' => 'You are a travel and tourism expert specializing in tour packages. Generate engaging marketing content for tour images.',
            'gallery' => 'You are a travel and tourism expert specializing in travel photography. Generate descriptive content for gallery images.',
            'banner' => 'You are a travel and tourism expert specializing in website banner design. Generate compelling marketing content for banner images.',
        ];

        return $prompts[$category] ?? 'You are a travel and tourism expert. Generate content for travel images.';
    }

    protected function getUserPrompt(string $category): string
    {
        $type = ucfirst($category);

        return "Generate the following content for a {$type} image:\n\n".
            "1. Title (10-15 words, marketing-focused)\n".
            "2. Description (30-40 words, tourism-focused, engaging)\n".
            "3. Alt Text (10-15 words, SEO-optimized, descriptive)\n".
            "4. Tags (5-7 SEO keywords as comma-separated values)\n\n".
            "Format your response EXACTLY as follows:\n".
            "TITLE: [your title]\n".
            "DESCRIPTION: [your description]\n".
            "ALT_TEXT: [your alt text]\n".
            'TAGS: [comma separated tags]';
    }

    protected function parseGeneratedContent(string $content): array
    {
        $lines = explode("\n", trim($content));
        $data = [
            'title' => 'Amazing Travel Experience',
            'description' => 'Discover unforgettable moments on your next adventure.',
            'alt_text' => 'Beautiful travel destination image',
            'tags' => ['travel', 'adventure', 'tourism'],
        ];

        foreach ($lines as $line) {
            $line = trim($line);
            if (str_starts_with($line, 'TITLE:')) {
                $data['title'] = trim(str_replace('TITLE:', '', $line));
            } elseif (str_starts_with($line, 'DESCRIPTION:')) {
                $data['description'] = trim(str_replace('DESCRIPTION:', '', $line));
            } elseif (str_starts_with($line, 'ALT_TEXT:')) {
                $data['alt_text'] = trim(str_replace('ALT_TEXT:', '', $line));
            } elseif (str_starts_with($line, 'TAGS:')) {
                $tags = trim(str_replace('TAGS:', '', $line));
                $data['tags'] = array_map('trim', explode(',', $tags));
            }
        }

        return $data;
    }

    protected function getFallbackContent(string $category): array
    {
        $fallbacks = [
            'destination' => [
                'title' => 'Explore Amazing Destinations',
                'description' => 'Discover breathtaking locations and create memories that last a lifetime.',
                'alt_text' => 'Beautiful travel destination showcasing stunning scenery',
                'tags' => ['travel', 'destination', 'adventure', 'tourism', 'explore', 'vacation', 'holiday'],
            ],
            'tour' => [
                'title' => 'Unforgettable Tour Experience',
                'description' => 'Join us on an incredible journey to the most beautiful places on earth.',
                'alt_text' => 'Tour group enjoying amazing travel experiences',
                'tags' => ['tour', 'travel', 'adventure', 'tourism', 'journey', 'explore', 'experience'],
            ],
            'gallery' => [
                'title' => 'Travel Photography Gallery',
                'description' => 'Browse our collection of stunning travel photos from around the world.',
                'alt_text' => 'Beautiful travel photography in our image gallery',
                'tags' => ['photography', 'gallery', 'travel', 'photos', 'images', 'tourism', 'adventure'],
            ],
            'banner' => [
                'title' => 'Your Next Adventure Awaits',
                'description' => 'Start planning your dream vacation today and discover amazing destinations.',
                'alt_text' => 'Travel website banner with stunning destination imagery',
                'tags' => ['banner', 'travel', 'vacation', 'tourism', 'adventure', 'destination', 'holiday'],
            ],
        ];

        return $fallbacks[$category] ?? $fallbacks['destination'];
    }
}
