<?php
namespace App\Controller;

use Exception;
use App\Entity\Images;
use App\Form\ImagesFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Interfaces\TricksImagesManagementInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ImagesController extends AbstractController
{
    private TricksImagesManagementInterface $iIMages;

    public function __construct(TricksImagesManagementInterface $iIMages)
    {
        $this->iIMages = $iIMages;
    }

    /**
     * @Route("/update-image-{id}", name="update_img", methods={"GET", "POST"})
     * @Security("is_granted('ROLE_USER') and user === image.getTrick().getUser()")
     *
     * @param Request $request
     * @param Images $image
     * @return Response
     */
    public function update(Request $request, Images $image): Response
    {
        $form = $this->createForm(ImagesFormType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->iIMages->updateImage($image, $form);
            } catch (\Throwable $th) {
                return new Exception("Il y a un problème avec le serve de modification des images");
            }

            $this->addFlash('success', 'L\'image n° '.$image->getId().' a été correctement modifié');

            return $this->redirectToRoute('trick_update', ['id' => $image->getTrick()->getId()]);
        }

        return $this->render('images/edit.html.twig', [
            'title' => 'Choisissez une nouvelle image',
            'form'  => $form->createView()
        ]);
    }

    /**
     * @Route("/delete-image-{id}", name="delete_img", methods={"POST"})
     * @Security("is_granted('ROLE_USER') and user === image.getTrick().getUser()")
     *
     * @param Request $request
     * @param Images $image
     * @return Response
     */
    public function delete(Request $request, Images $image): Response
    {
        $tokenId = (string) 'delete' . $image->getId();

        if ($this->isCsrfTokenValid($tokenId, (string) $request->request->get('_token'))) {
            $lastImg = $image->getId();

            try {
                $this->iIMages->deleteImage($image);
            } catch (\Throwable $th) {
                return new Exception("Il y a un problème avec le service de suppression des images");
            }

            $this->addFlash(
                'success',
                'L\'image n° '. $lastImg .' a été correctement supprimée'
            );

            return $this->redirectToRoute('trick_update', ['id' => $image->getTrick()->getId()]);
        }

        return $this->redirectToRoute('trick_update', ['id' => $image->getTrick()->getId()]);
    }
}
