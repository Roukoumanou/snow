<?php 
namespace App\Services\Interfaces;

use App\Entity\Users;
use App\Entity\Tricks;
use App\Entity\Comments;

interface CommentsInterface
{
    public function addComment(Comments $comment, Tricks $tricks, Users $user): bool;
}
