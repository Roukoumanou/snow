<?php

namespace App\DataFixtures;

use App\Entity\Comments;
use App\Entity\Groups;
use App\Entity\Images;
use App\Entity\Tricks;
use App\Entity\Users;
use App\Entity\Videos;
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
                ->setAvatar('me-622e04c2a52a7.jpg')
                ->setEmail('roukoumanouamidou@gmail.com')
                ->setRoles([]);

        $encodedPassword = $this->encoder->hashPassword(
            $user,
            'password'
        );

        $user->setPassword($encodedPassword);

        $manager->persist($user);

        $trickNumber = 1;

        foreach ($this->getGroups() as $group) {
            $newGroupe = (new Groups())
                ->setName($group);

            $manager->persist($newGroupe);

            $trick = (new Tricks())
                ->setName('Figure n°'.$trickNumber)
                ->setDescription('Une petite description sans faker :-)')
                ->setUser($user)
                ->setGroup($newGroupe)
                ->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($trick);

            foreach ($this->getImages() as $name) {
                $img = (new Images())
                    ->setName($name)
                    ->setTrick($trick)
                    ->setCreatedAt(new \DateTimeImmutable());

                $manager->persist($img);
            }

            foreach ($this->getVideosUrl() as $url) {
                $video = (new Videos())
                    ->setName($url)
                    ->setTrick($trick)
                    ;

                $manager->persist($video);
            }

            for ($i=1; $i < rand(2, 10) ; $i++) { 
                $comment = (new Comments())
                    ->setMessage('Voici le commentaire n°'.$i.' de cette figure')
                    ->setUser($user)
                    ->setTrick($trick)
                    ->setCreatedAt(new \DateTimeImmutable());

                $manager->persist($comment);
            }

            $trickNumber ++;
            
        }

        $manager->flush();
    }

    private function getGroups(): array
    {
        return [
            'XL',
            'Zeach',
            '360',
            '540',
            '720',
            '1080'
        ];
    }

    private function getImages(): array
    {
        $imgs = [
                'snowboard-1-623a469ebabe8.jpg', 
                'ninety-ninety-123-623a469ebae6b.jpg',
                'snowboard1170x508-623a469ebb39a.jpg'
            ];

        shuffle($imgs);

        return $imgs;
    }

    private function getVideosUrl(): array
    {
        return [
            'aPhYdeitDtA',
            'pYJbes1VChQ',
            'PmFJ-MG9VPo'
        ];
    }
}
