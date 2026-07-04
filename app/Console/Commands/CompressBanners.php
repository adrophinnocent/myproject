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
    protected $description = 'Compress and convert all banner images to WebP format';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $directory = public_path('images/banners');

        if (!File::isDirectory($directory)) {
            $this->error("Directory $directory does not exist.");
            return;
        }

        $files = File::files($directory);
        $count = 0;

        foreach ($files as $file) {
            $extension = strtolower($file->getExtension());

            if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $this->info("Processing: " . $file->getFilename());

                $source = imagecreatefromstring(file_get_contents($file->getPathname()));
                if ($source) {
                    $filename = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                    $destination = $directory . '/' . $filename . '.webp';

                    // Save as WebP
                    imagewebp($source, $destination, 80);
                    imagedestroy($source);

                    $this->comment("Converted to WebP: $filename.webp");

                    // Optional: Delete the original if you want
                    // File::delete($file->getPathname());

                    $count++;
                }
            }
        }

        $this->info("Successfully processed $count images.");
    }
}
