<?php

declare(strict_types=1);

namespace App\Domains\Packagist\Data;

class PackageData
{
    public readonly string $imageUrl;

    public function __construct(
        public readonly string $name,
        public readonly string $repository,
        public readonly bool $abandoned,
    ) {
        $this->imageUrl = str('https://opengraph.githubassets.com/')
            ->append(now()->toDateString())
            ->append('/')
            ->append($this->name)
            ->toString();
    }
}
