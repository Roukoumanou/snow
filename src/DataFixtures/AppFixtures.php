<?php

namespace App\DataFixtures;

use App\Entity\Groups;
use App\Entity\Users;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $encoder;

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

        foreach ($this->getGroups() as $group) {
            $newGroupe = (new Groups())
                ->setName($group);

            $manager->persist($newGroupe);

        }

        $manager->flush();
    }

    private function getGroups(): array
    {
        return [
            'Noseslide',
            'Pop/poper',
            'Quarter pipe',
            'Rodeoback / Rodeofront',
            'Slopestyle',
            'Twin tip',
            'Underflip',
            'Vitelli Turn',
            'Wildcat',
            'XL',
            'Zeach',
            '360, 540, 720, 1080'
        ];
    }
}
