<?php 
namespace App\Services\DomainCode;

use App\Entity\Users;
use App\Entity\Tricks;
use App\Entity\Comments;
use App\Repository\CommentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\Interfaces\CommentsInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class CommentsService implements CommentsInterface
{
    private EntityManagerInterface $em;

    private CommentsRepository $commentRepo;

    public function __construct(EntityManagerInterface $em, CommentsRepository $commentRepo)
    {
        $this->em = $em;
        $this->commentRepo = $commentRepo;
    }

    public function addComment(Comments $comment, Tricks $trick, Users $user): bool
    {
        $comment->setTrick($trick)
            ->setUser($user);

        $this->em->persist($comment);
        $this->em->flush();

        return true;
    }

    public function getComments(Tricks $trick, int $offset): Paginator
    {
        return $this->commentRepo->getPaginationComments($trick, $offset);
    }
}
