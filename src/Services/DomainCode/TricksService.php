<?php 

namespace App\Services\DomainCode;

use App\Entity\Images;
use App\Entity\Tricks;
use App\Form\TrickFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\Interfaces\TricksInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Services\Interfaces\TricksImagesUploaderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TricksService extends AbstractController implements TricksInterface
{
    private EntityManagerInterface $em;

    private TricksImagesUploaderInterface $uploader;

    public function __construct(
        EntityManagerInterface $em,
        TricksImagesUploaderInterface $uploader
    )
    {
        $this->em = $em;
        $this->uploader = $uploader;
    }

    public function new(Request $request): Response
    {
        $trick = new Tricks();

        $form = $this->createForm(TrickFormType::class, $trick);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if (count($form->get('images')->getData()) > 0) {
                foreach ($form->get('images')->getData() as $value) {
                    /** @var UploadedFile $image */
                    $image = $value->getFileType();
                    $fileName = $this->uploader->upload($image);
                    
                        $value->setName($fileName)
                        ->setTrick($trick);

                    $this->em->persist($value);
                }
                
                $trick->setUser($this->getUser());
                $this->em->persist($trick);
                $this->em->flush();
            }

            $this->addFlash(
                'success', 'La figure a corrèctement été rajouté'
            );

            return $this->redirectToRoute('app_home');
        }

        return $this->render('tricks/new.html.twig', [
            'title' => "Ajouter une figure",
            'form' => $form->createView()
        ]);
    }

    public function show(Tricks $trick): Response
    {
        return $this->render('tricks/show.html.twig', [
            'title' => $trick->getName(),
            'images' => $trick->getImages(),
            'trick' => $trick
        ]);
    }

    public function update(Request $request, Tricks $trick): Response
    {
        return $this->render('');
    }

    public function delete(Tricks $trick): Response
    {
        return $this->render('');
    }
}