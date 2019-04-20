<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Users\Domain\User;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    private $data = [
        ['nauczyciel', 'nauczyciel', User::ROLE_LECTURER],
        ['kierownik', 'kierownik', User::ROLE_DISTRICT_MANAGER],
        ['dziekan', 'dziekan', User::ROLE_DEAN],
        ['planista', 'planista', User::ROLE_LECTURER],
        ['admin', 'admin', User::ROLE_ADMIN]
    ];

    public function load(ObjectManager $manager)
    {
        foreach ($this->data as $item) {
            $user = new User($item[0]);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $item[1]
            ));
            $user->setRoles([$item[2]]);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
