<?php

namespace App\DataFixtures;

use App\Factory\CategoryFactory;
use App\Factory\GameFactory;
use App\Factory\TagFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        CategoryFactory::createMany(5);
        GameFactory::createMany(
            100, function () {
            return ['category' => CategoryFactory::random()];
        }
        );
        TagFactory::new()->many(25)->create(
            function () {
                return ['games' => GameFactory::randomRange(1, 10)];
            }
        );

    }
}
