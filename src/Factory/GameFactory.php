<?php

namespace App\Factory;

use App\Entity\Game;
use App\Repository\GameRepository;
use phpDocumentor\Reflection\Types\Self_;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Game|Proxy createOne(array $attributes = [])
 * @method static Game[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Game|Proxy find($criteria)
 * @method static Game|Proxy findOrCreate(array $attributes)
 * @method static Game|Proxy first(string $sortedField = 'id')
 * @method static Game|Proxy last(string $sortedField = 'id')
 * @method static Game|Proxy random(array $attributes = [])
 * @method static Game|Proxy randomOrCreate(array $attributes = [])
 * @method static Game[]|Proxy[] all()
 * @method static Game[]|Proxy[] findBy(array $attributes)
 * @method static Game[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Game[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static GameRepository|RepositoryProxy repository()
 * @method Game|Proxy create($attributes = [])
 */
final class GameFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            'name' => ucfirst(self::faker()->words(3, true)),
            'description' => self::faker()->text(200),
            'data' => self::faker()->url,
            'img' => self::faker()->imageUrl(200, 150, 'games'),
            'keywords' => self::faker()->words(10, true),
            'isPublished' => self::faker()->boolean(80),
            'createdAt' => self::faker()->dateTimeBetween('-2 week', '-1 week'),
            'rating' => self::faker()->numberBetween(1,1000)
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this->afterInstantiate(function (Game $game) {
            if (self::faker()->boolean(70)) {
                $game->setUpdatedAt(self::faker()->dateTimeBetween($game->getCreatedAt(), '+1 week'));
            }
        });
    }

    protected static function getClass(): string
    {
        return Game::class;
    }
}
