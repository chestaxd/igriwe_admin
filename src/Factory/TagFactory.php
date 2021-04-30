<?php

namespace App\Factory;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Tag|Proxy createOne(array $attributes = [])
 * @method static Tag[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Tag|Proxy find($criteria)
 * @method static Tag|Proxy findOrCreate(array $attributes)
 * @method static Tag|Proxy first(string $sortedField = 'id')
 * @method static Tag|Proxy last(string $sortedField = 'id')
 * @method static Tag|Proxy random(array $attributes = [])
 * @method static Tag|Proxy randomOrCreate(array $attributes = [])
 * @method static Tag[]|Proxy[] all()
 * @method static Tag[]|Proxy[] findBy(array $attributes)
 * @method static Tag[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Tag[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static TagRepository|RepositoryProxy repository()
 * @method Tag|Proxy create($attributes = [])
 */
final class TagFactory extends ModelFactory
{

    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->word(),
            'description' => self::faker()->text(200)
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this// ->afterInstantiate(function(Tag $tag) {})
            ;
    }

    protected static function getClass(): string
    {
        return Tag::class;
    }
}
