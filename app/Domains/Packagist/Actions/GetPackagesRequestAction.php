<?php

namespace App\Domains\Packagist\Actions;

use App\Domains\Packagist\Data\PackageData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class GetPackagesRequestAction
{
    use AsAction;

    /** @return Collection<PackageData> */
    public function handle(): Collection
    {
        $path = base_path('packagist.cache.json');

        $results = collect(@json_decode(@file_get_contents($path), true));

        if ($results->isEmpty()) {
            $results = Http::retry(3, 400)
                ->withUserAgent('danielmason.co.uk | fidum.dev@gmail.com')
                ->throw()
                ->get('https://packagist.org/packages/list.json', [
                    'vendor' => 'fidum',
                    'fields' => ['repository', 'abandoned'],
                ])
                ->collect('packages');

            file_put_contents($path, $results->toJson());
        }

        $uuid = Str::orderedUuid();

        return $results->map(fn (array $data, string $name) => new PackageData(
            name: $name,
            repository: $data['repository'],
            abandoned: (bool) $data['abandoned'],
            uuid: $uuid,
        ));
    }
}
