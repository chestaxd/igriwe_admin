<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Factory\CategoryFactory;
use App\Factory\GameFactory;
use App\Factory\TagFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        GameFactory::createMany(
            200, function () {
            return ['category' => CategoryFactory::random()];
        }
        );
        TagFactory::new()->many(25)->create(
            function () {
                return ['games' => GameFactory::randomRange(30, 50)];
            }
        );
        $manager->flush();
    }
}
