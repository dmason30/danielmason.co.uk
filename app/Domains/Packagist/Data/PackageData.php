<?php

namespace App\Domains\Packagist\Data;

use Illuminate\Support\Str;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class PackageItemData extends Data
{
    #[Computed]
    public readonly string $githubUrl;

    #[Computed]
    public readonly string $imageUrl;

    public function __construct(
        public readonly string $name,
        public readonly bool $abandoned,
    )
    {
        $this->githubUrl = "https://gitub.com/$this->name";
        $this->imageUrl = str("https://opengraph.githubassets.com/")
            ->append(Str::orderedUuid())
            ->append('/')
            ->append($this->name);
    }
}