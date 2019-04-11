<?php

namespace App\Fixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User('admin');
        $user->setPassword('admin');
        $user->addRole(new Role('Admin'));
        $user->setIsActive(true);

        $manager->persist($user);
        $manager->flush();
    }
}