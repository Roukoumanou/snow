<?php 

namespace App\Services\DomainCode;

use App\Entity\Tricks;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\Interfaces\TricksInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Services\Interfaces\TricksImagesUploaderInterface;
use Symfony\Component\Form\Form;

class TricksService implements TricksInterface
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

    public function new(Tricks $trick, Form $form, Users $user): bool
    {
        // Gestion des images 
        if (count($form->get('images')->getData()) > 0) {
            foreach ($form->get('images')->getData() as $value) {
                /** @var UploadedFile $image */
                $image = $value->getFileType();
                $fileName = $this->uploader->upload($image);
                
                    $value->setName($fileName)
                        ->setTrick($trick);

                $this->em->persist($value);
            }
            
        }

        // gestion des videos
        if (count($form->get('videos')->getData()) > 0) {
            foreach ($form->get('videos')->getData() as $value) {
                
                $value->setTrick($trick);

                $this->em->persist($value);
            }
            
        }

        $trick->setUser($user);
        $this->em->persist($trick);
        $this->em->flush();

        return true;
    }

    public function update(Tricks $trick, Form $form): bool
    {
        // Gestion des images 
        if (count($form->get('images')->getData()) > 0) {
            foreach ($form->get('images')->getData() as $value) {
                /** @var UploadedFile $image */
                $image = $value->getFileType();
                $fileName = $this->uploader->upload($image);
                
                    $value->setName($fileName)
                        ->setTrick($trick);

                $this->em->persist($value);
            }
            
        }

        // gestion des videos
        if (count($form->get('videos')->getData()) > 0) {
            foreach ($form->get('videos')->getData() as $value) {
                
                $value->setTrick($trick);

                $this->em->persist($value);
            }
            
        }

        $this->em->flush();
        
        return true;
    }

    public function delete(Tricks $trick): bool
    {
        // Suppression des images liées
        if (count($trick->getImages()) > 0) {
            foreach ($trick->getImages() as $image) {
                $this->em->remove($image);
                unlink(dirname(__DIR__, 3).'/public/uploads/tricks/'.$image->getName());
            }
        }

        // Suppression des vidéos liées
        if (count($trick->getVideos()) > 0) {
            foreach ($trick->getVideos() as $video) {
                $this->em->remove($video);
            }
        }

        $this->em->remove($trick);
        $this->em->flush();

        return true;
    }
}
