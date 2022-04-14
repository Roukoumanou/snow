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

        // Je construis les groupes
        $groups = [];
        foreach ($this->getGroups() as $group) {
            $newGroupe = (new Groups())
                ->setName($group);

            $manager->persist($newGroupe);

            $groups[] = $newGroupe;
        }

        // Je construis les tricks
        foreach ($this->getTricks() as $key => $value) {

            $types = ['switch', 'tail'];
            $grabs = ['indy', 'mute', 'nose grab', 'melon', 'stalefish', 'tail grab'];

            shuffle($types);
            shuffle($grabs);
            shuffle($groups);

            $type = $types['1'];
            $grab = $grabs['0'];
            $group = $groups['0'];

            $trick = (new Tricks())
                ->setName($type . ' ' . $key . ' ' . $grab)
                ->setDescription($value)
                ->setUser($user)
                ->setGroup($group)
                ->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($trick);

            // Je crée une collection de trois d'images pour un trick
            foreach ($this->getImages() as $name) {
                $img = (new Images())
                    ->setName($name)
                    ->setTrick($trick)
                    ->setCreatedAt(new \DateTimeImmutable());

                $manager->persist($img);
            }

            // Je crée une collection de trois vidéos pour un trick
            foreach ($this->getVideosUrl() as $url) {
                $video = (new Videos())
                    ->setName($url)
                    ->setTrick($trick);

                $manager->persist($video);
            }

            // Je crée une collection de commentaire pour un trick
            for ($i = 1; $i < rand(2, 10); $i++) {
                $comment = (new Comments())
                    ->setMessage('Belle figure. J\'aime la figure ' . $trick->getName())
                    ->setUser($user)
                    ->setTrick($trick)
                    ->setCreatedAt(new \DateTimeImmutable());

                $manager->persist($comment);
            }
        }

        $manager->flush();
    }

    private function getGroups(): array
    {
        return [
            '180',
            '360',
            '540',
            '720',
            '1080'
        ];
    }

    private function getTricks(): array
    {
        return [
            'Air to Fakie' => "Il s'agit d'une figure relativement simple, 
                et plus précisément d'un saut sans rotation qui se fait généralement dans un pipe (un U). 
                Le rider s'élance dans les airs et retombe dans le sens inverse.",
            'Big air' => "C'est l'une des épreuves les plus impressionnantes dans les compétitions de snow. 
                rider s’élance à une vitesse folle avant de sauter sur une tremplin et de réaliser un maximum de tricks dans les airs. 
                Le big air peut aussi faire référence au tremplin de neige duquel le snowboardeur s'élance avant de faire ses figures.",
            'Carver' => "C'est un mot qui revient souvent dans la bouche des snowboardeurs. Mais pas que, puisqu'on parle aussi de carving en skis. 
                Mais alors qu'est-ce que c'est ? Carver, c'est tout simplement faire un virage net en se penchant et sans déraper.",
            'Dual Snowboard' => "Assez décrié, le dual snowboard est considéré par beaucoup comme l'une des inventions les plus inutiles
                (et ridicules) de ces dernières années dans le monde de la glisse. Concrètement il s'agit de mini-patinettes spécialement conçues pour les tricks. Voilà voilà.",
            'Embase' => "C’est le corps de la fixation sur laquelle la boots est posée. 
                Les matériaux utilisés déterminent en grande partie le flex général de l’embase, soit sa capacité à se déformer.",
            'Fart' => "Le fartage consiste à appliquer (à froid ou à chaud) une paraffine spéciale qui permet de la créer une micro 
                pellicule d'eau entre la semelle et la neige sur laquelle la planche pourra glisser. Un fartage régulier permet de mieux glisser et d’entretenir la longévité de la board.",

            'Half-pipe' => "C’est un double tremplin en forme de U. 
                Les snowboarders doivent réussir à descendre le long de la rampe d’un côté et remonter le plus vite possible de l’autre côté, en réussissant des tricks quand ils sont en l’air. 
                Un half-pipe classique mesure environ 5 mètres de haut et 120 mètres de long.",
            'Inserts' => "Les inserts sont les trous situés sur le dessus de la board sur lesquels les fixations sont vissées. 
                Généralement, une fixation est composée de sangles ou « straps » avec des crans de réglage. 
                Les fix classiques sont composées de deux sangles, une pour maintenir la cheville, une autre pour le bout des pieds.s",
            'Jib' => "Le Jib (aussi appelé slide ou grind) est une pratique du snow freestyle qui consiste à glisser sur tous types de modules autres que la neige (rails, troncs d'arbre, tables etc.)",
            'Kicker' => "C'est la partie la plus haute d'une bosse sur laquelle les snowboardeurs s'appuient - ou 'pop' (voir plus bas) - pour s'élancer avant un saut."
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
