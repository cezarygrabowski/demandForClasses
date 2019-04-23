<?php


namespace App\DataFixtures;


use Demands\Domain\Place;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PlacesFixtures extends Fixture
{
    private const DATA = [
        [65, 100],
        [65, 101],
        [65, 102],
        [65, 103],
        [65, 104],
        [65, 105],
        [230, 100],
        [230, 101],
        [230, 102],
        [230, 103],
        [230, 104],
        [230, 105]
    ];

    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::DATA as $item) {
            $place = new Place($item[0], $item[1]);
            $manager->persist($place);
        }

        $manager->flush();
    }
}
