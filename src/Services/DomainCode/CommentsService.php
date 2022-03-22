<?php 
namespace App\Services\DomainCode;

use App\Entity\Users;
use App\Entity\Tricks;
use App\Entity\Comments;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\Interfaces\CommentsInterface;

class CommentsService implements CommentsInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function addComment(Comments $comment, Tricks $trick, Users $user): bool
    {
        $comment->setTrick($trick)
            ->setUser($user);

        $this->em->persist($comment);
        $this->em->flush();

        return true;
    }
}
