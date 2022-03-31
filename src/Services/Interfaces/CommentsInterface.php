<?php 
namespace App\Services\Interfaces;

use App\Entity\Users;
use App\Entity\Tricks;
use App\Entity\Comments;
use Doctrine\ORM\Tools\Pagination\Paginator;

interface CommentsInterface
{
    public function addComment(Comments $comment, Tricks $tricks, Users $user): bool;

    public function getComments(Tricks $trick, int $offset): Paginator;
}
