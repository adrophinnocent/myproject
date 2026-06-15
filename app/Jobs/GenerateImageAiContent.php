<?php

namespace App\Jobs;

use App\Models\AiImage;
use App\Services\AIGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateImageAiContent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $aiImage;

    public function __construct(AiImage $aiImage)
    {
        $this->aiImage = $aiImage;
    }

    public function handle(AIGenerator $aiGenerator): void
    {
        try {
            $aiContent = $aiGenerator->generateImageContent(
                $this->aiImage->image_path,
                $this->aiImage->category
            );

            $this->aiImage->update([
                'title' => $aiContent['title'],
                'description' => $aiContent['description'],
                'alt_text' => $aiContent['alt_text'],
                'tags' => $aiContent['tags'],
            ]);

            Log::info("AI content generated for image ID: {$this->aiImage->id}");
        } catch (\Exception $e) {
            Log::error("Failed to generate AI content for image ID {$this->aiImage->id}: " . $e->getMessage());
        }
    }
}
