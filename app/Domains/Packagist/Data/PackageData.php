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
        private readonly string $uuid,
    ) {
        $this->imageUrl = str('https://opengraph.githubassets.com/')
            ->append($uuid)
            ->append('/')
            ->append($this->name)
            ->toString();
    }
}
