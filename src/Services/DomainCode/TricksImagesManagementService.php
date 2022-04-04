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
        $oldImage = clone $image;
        
        $image->setName($fileName)
        ->setUpdatedAt(new \DateTimeImmutable());
        
        $this->em->flush();

        $this->unlinkImage($oldImage);
        

        return true;
    }

    public function deleteImage(Images $image): bool
    {
        $oldImage = $image;

        $this->em->remove($image);
        $this->em->flush();

        $this->unlinkImage($oldImage);

        return true;
    }

    private function unlinkImage(Images $image): bool
    {
        $demoImg = [
            'snowboard-1-623a469ebabe8.jpg', 
            'ninety-ninety-123-623a469ebae6b.jpg', 
            'snowboard1170x508-623a469ebb39a.jpg'];

        if (! in_array($image->getName(), $demoImg)) {
            unlink(dirname(__DIR__, 3).'/public/uploads/tricks/'.$image->getName());
        }

        return true;
    }
}
