<?php 

namespace App\Controller;

use App\Entity\Tricks;
use App\Form\TrickFormType;
use App\Services\Interfaces\TricksInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TricksController extends AbstractController
{
    private TricksInterface $iTricks;

    public function __construct(TricksInterface $iTricks)
    {
        $this->iTricks = $iTricks;
    }

    /**
     * Permet d'enrégistration une figure
     * 
     * @Route("/trick-new", name="trick_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @return Response
     */
    public function newTrick(Request $request): Response
    {
        $trick = new Tricks();

        $form = $this->createForm(TrickFormType::class, $trick);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $this->iTricks->new($trick, $form, $this->getUser());

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

    /**
     * @Route("/trick-{id}-show", name="trick_show", methods={"GET", "POST"})
     *
     * @param Tricks $trick
     * @return Response
     */
    public function showTrick(Tricks $trick): Response
    {
        return $this->render('tricks/show.html.twig', [
            'title' => $trick->getName(),
            'images' => $trick->getImages(),
            'trick' => $trick
        ]);
    }

    /**
     * @Route("/trick-{id}-update", name="trick_update", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param Tricks $trick
     * @return Response
     */
    public function updateTrick(Request $request, Tricks $trick): Response
    {
        $form = $this->createForm(TrickFormType::class, $trick);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $this->iTricks->update($trick, $form);

            $this->addFlash(
                'success', 'La figure a corrèctement été modifié'
            );

            return $this->redirectToRoute('app_home');
        } 

        return $this->render('tricks/update.html.twig', [
            'title' => "Modification|".$trick->getName(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/trick-{id}-delete", name="trick_delete", methods={"POST"})
     *
     * @param Request $request
     * @param Tricks $trick
     * @return Response
     */
    public function delete(Request $request, Tricks $trick): Response
    {
        if ($this->isCsrfTokenValid('delete' . $trick->getId(), $request->request->get('_token'))) {
            $this->iTricks->delete($trick);

            $this->addFlash(
                'success',
                'La La figure a été correctement supprimée'
            );

            return $this->redirectToRoute('app_home');
        }
    }
}
