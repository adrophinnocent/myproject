<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CompressBanners extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'banners:compress';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compress and convert all banner, destination, and tour images to WebP format';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $directories = [
            public_path('images/banners'),
            public_path('images/kilimanjaro'),
            public_path('images/logos'),
            // Persistent storage folders
            base_path('../../twina_assets/destinations'),
            base_path('../../twina_assets/tours'),
            base_path('../../twina_assets/media'),
        ];

        $count = 0;
        foreach ($directories as $directory) {
            if (!File::isDirectory($directory)) {
                $this->warn("Directory $directory does not exist. Skipping.");
                continue;
            }

            $this->info("Scanning: $directory");
            $files = File::files($directory);

            foreach ($files as $file) {
                $extension = strtolower($file->getExtension());

                if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                    $this->info("Processing: " . $file->getFilename());

                    try {
                        $source = @imagecreatefromstring(file_get_contents($file->getPathname()));
                        if ($source) {
                            $width = imagesx($source);
                            $height = imagesy($source);

                            // Resize if too large (Max 1920px width)
                            if ($width > 1920) {
                                $newWidth = 1920;
                                $newHeight = ($height / $width) * $newWidth;
                                $scaled = imagecreatetruecolor($newWidth, $newHeight);
                                imagealphablending($scaled, false);
                                imagesavealpha($scaled, true);
                                imagecopyresampled($scaled, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                                imagedestroy($source);
                                $source = $scaled;
                            }

                            $filename = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                            $destination = $directory . '/' . $filename . '.webp';

                            // Save as WebP with good quality
                            imagewebp($source, $destination, 80);
                            imagedestroy($source);

                            $this->comment("Converted to WebP: $filename.webp");
                            $count++;
                        }
                    } catch (\Throwable $e) {
                        $this->error("Failed to process " . $file->getFilename() . ": " . $e->getMessage());
                    }
                }
            }
        }

        $this->info("Successfully processed $count images.");
    }
}
