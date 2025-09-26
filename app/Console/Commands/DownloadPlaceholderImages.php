<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DownloadPlaceholderImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:download-placeholder-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download placeholder images for note seeding';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Downloading placeholder images...');
        
        // Create notes directory if it doesn't exist
        $directory = storage_path('app/public/notes');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
        
        // List of placeholder image URLs
        $placeholders = [
            'https://picsum.photos/800/600?random=1' => 'notes/placeholder-1.jpg',
            'https://picsum.photos/800/600?random=2' => 'notes/placeholder-2.jpg',
            'https://picsum.photos/800/600?random=3' => 'notes/placeholder-3.jpg',
            'https://picsum.photos/800/600?random=4' => 'notes/placeholder-4.jpg',
            'https://picsum.photos/800/600?random=5' => 'notes/placeholder-5.jpg',
        ];
        
        foreach ($placeholders as $url => $path) {
            $this->info("Downloading: $url");
            $contents = file_get_contents($url);
            
            if ($contents) {
                Storage::disk('public')->put($path, $contents);
                $this->info("Saved to: $path");
            } else {
                $this->error("Failed to download: $url");
            }
        }
        
        $this->info('Placeholder images downloaded successfully!');
    }
}
