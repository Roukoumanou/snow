<?php
namespace App\Services\DomainCode;

use App\Entity\Images;
use Symfony\Component\Form\Form;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Services\Interfaces\TricksImagesUploaderInterface;
use App\Services\Interfaces\TricksImagesManagementInterface;

class TricksImagesManagementService implements TricksImagesManagementInterface
{
    private EntityManagerInterface $em;

    private TricksImagesUploaderInterface $uploader;

    public function __construct(
        EntityManagerInterface $em,
        TricksImagesUploaderInterface $uploader
    ){
        $this->em = $em;
        $this->uploader = $uploader;
    }

    public function updateImage(Images $image, Form $form): bool
    {
        /** @var UploadedFile $newImage */
        $newImage = $form->get('fileType')->getData();

        $fileName = $this->uploader->upload($newImage);
        
        $oldImage = $image->getName();
        
        $image->setName($fileName)
        ->setUpdatedAt(new \DateTimeImmutable());
        
        $this->em->flush();
        
        unlink(dirname(__DIR__, 3).'/public/uploads/tricks/'.$oldImage);

        return true;
    }

    public function deleteImage(Images $image): bool
    {
        $this->em->remove($image);
        $this->em->flush();

        return true;
    }
}
