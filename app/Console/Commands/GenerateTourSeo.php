<?php

namespace App\Console\Commands;

use App\Models\Tour;
use App\Services\AIGenerator;
use Illuminate\Console\Command;

class GenerateTourSeo extends Command
{
    protected $signature = 'tour:generate-seo {--force : Overwrite existing metadata}';
    protected $description = 'Generate SEO metadata for tours using AI';

    public function handle(AIGenerator $ai)
    {
        $query = Tour::query();

        if (!$this->option('force')) {
            $query->where(function ($q) {
                $q->whereNull('meta_title')
                  ->orWhereNull('meta_description')
                  ->orWhere('meta_title', '')
                  ->orWhere('meta_description', '');
            });
        }

        $tours = $query->get();

        if ($tours->isEmpty()) {
            $this->info('No tours found that need SEO metadata.');
            return;
        }

        $this->info("Found {$tours->count()} tours to process.");

        foreach ($tours as $tour) {
            $this->info("Processing: {$tour->title}...");

            $metadata = $ai->generateSeoMetadata($tour->title, $tour->description ?? $tour->short_description);

            if (!empty($metadata)) {
                $tour->update($metadata);
                $this->info("Successfully updated SEO for: {$tour->title}");
            } else {
                $this->error("Failed to generate SEO for: {$tour->title}");
            }
        }

        $this->info('SEO generation complete!');
    }
}
