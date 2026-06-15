<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        try {
            // First, let's create a system prompt based on category
            $systemPrompt = $this->getSystemPrompt($category);
            $userPrompt = $this->getUserPrompt($category);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/chat/completions', [
                'model' => 'gpt-4-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt]
                ],
                'max_tokens' => 500,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $content = $response->json()['choices'][0]['message']['content'];
                return $this->parseGeneratedContent($content);
            }

            return $this->getFallbackContent($category);
        } catch (\Exception $e) {
            Log::error('AI Generation failed: ' . $e->getMessage());
            return $this->getFallbackContent($category);
        }
    }

    protected function getSystemPrompt(string $category): string
    {
        $prompts = [
            'destination' => 'You are a travel and tourism expert specializing in destination marketing. Generate SEO-friendly content for travel images.',
            'tour' => 'You are a travel and tourism expert specializing in tour packages. Generate engaging marketing content for tour images.',
            'gallery' => 'You are a travel and tourism expert specializing in travel photography. Generate descriptive content for gallery images.',
            'banner' => 'You are a travel and tourism expert specializing in website banner design. Generate compelling marketing content for banner images.'
        ];

        return $prompts[$category] ?? 'You are a travel and tourism expert. Generate content for travel images.';
    }

    protected function getUserPrompt(string $category): string
    {
        $type = ucfirst($category);
        return "Generate the following content for a {$type} image:\n\n" .
            "1. Title (10-15 words, marketing-focused)\n" .
            "2. Description (30-40 words, tourism-focused, engaging)\n" .
            "3. Alt Text (10-15 words, SEO-optimized, descriptive)\n" .
            "4. Tags (5-7 SEO keywords as comma-separated values)\n\n" .
            "Format your response EXACTLY as follows:\n" .
            "TITLE: [your title]\n" .
            "DESCRIPTION: [your description]\n" .
            "ALT_TEXT: [your alt text]\n" .
            "TAGS: [comma separated tags]";
    }

    protected function parseGeneratedContent(string $content): array
    {
        $lines = explode("\n", trim($content));
        $data = [
            'title' => 'Amazing Travel Experience',
            'description' => 'Discover unforgettable moments on your next adventure.',
            'alt_text' => 'Beautiful travel destination image',
            'tags' => ['travel', 'adventure', 'tourism']
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
                'tags' => ['travel', 'destination', 'adventure', 'tourism', 'explore', 'vacation', 'holiday']
            ],
            'tour' => [
                'title' => 'Unforgettable Tour Experience',
                'description' => 'Join us on an incredible journey to the most beautiful places on earth.',
                'alt_text' => 'Tour group enjoying amazing travel experiences',
                'tags' => ['tour', 'travel', 'adventure', 'tourism', 'journey', 'explore', 'experience']
            ],
            'gallery' => [
                'title' => 'Travel Photography Gallery',
                'description' => 'Browse our collection of stunning travel photos from around the world.',
                'alt_text' => 'Beautiful travel photography in our image gallery',
                'tags' => ['photography', 'gallery', 'travel', 'photos', 'images', 'tourism', 'adventure']
            ],
            'banner' => [
                'title' => 'Your Next Adventure Awaits',
                'description' => 'Start planning your dream vacation today and discover amazing destinations.',
                'alt_text' => 'Travel website banner with stunning destination imagery',
                'tags' => ['banner', 'travel', 'vacation', 'tourism', 'adventure', 'destination', 'holiday']
            ]
        ];

        return $fallbacks[$category] ?? $fallbacks['destination'];
    }
}
