<?php
namespace App\Controller;

use App\Entity\Images;
use App\Form\ImagesFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Interfaces\TricksImagesManagementInterface;
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
            $this->iIMages->updateImage($image, $form);

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
     *
     * @param Request $request
     * @param Images $image
     * @return Response
     */
    public function delete(Request $request, Images $image): Response
    {
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $request->request->get('_token'))) {
            $this->iIMages->deleteImage($image);

            $this->addFlash(
                'success',
                'L\'image n° '. $image->getId() .' a été correctement supprimée'
            );

            return $this->redirectToRoute('trick_update', ['id' => $image->getTrick()->getId()]);
        }

        return $this->redirectToRoute('trick_update', ['id' => $image->getTrick()->getId()]);
    }
}
