<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = (new Users())
                ->setName('Roukoumanou Amidou')
                ->setEmail('roukoumanouamidou@gmail.com')
                ->setRoles([]);

        $encodedPassword = $this->encoder->hashPassword(
            $user,
            'password'
        );

        $user->setPassword($encodedPassword);

        $manager->persist($user);

        $manager->flush();
    }
}
