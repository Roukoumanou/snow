<?php 
namespace App\Services\DomainCode;

use App\Entity\Videos;
use Symfony\Component\Form\Form;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\Interfaces\TricksVideosManagementInterface;

class TricksVideosManagementService implements TricksVideosManagementInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function updateVideo(Videos $video, Form $form): bool
    {
        $video->setName($form->get('name')->getData());
        $this->em->flush();

        return true;
    }

    public function deleteVideo(Videos $video): bool
    {
        $this->em->remove($video);
        return true;
    }
}
