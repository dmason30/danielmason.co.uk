<?php

declare(strict_types=1);

namespace App\Domains\Packagist\Data;

use Illuminate\Support\Facades\File;

class PackageData
{
    public readonly string $imageUrl;

    public function __construct(
        public readonly string $name,
        public readonly string $repository,
    ) {
        $this->imageUrl = "/assets/github/$this->name.png";
        $assetPath = base_path("_site/$this->imageUrl");

        if (! file_exists($assetPath)) {
            $githubUrl = str('https://opengraph.githubassets.com/')
                ->append(now()->toDateString())
                ->append('/')
                ->append($this->name)
                ->toString();

            $contents = retry(
                times: 10,
                callback: fn () => file_get_contents($githubUrl),
                sleepMilliseconds: 1000,
            );

            File::ensureDirectoryExists(dirname($assetPath));
            File::put($assetPath, $contents);
        }
    }
}
