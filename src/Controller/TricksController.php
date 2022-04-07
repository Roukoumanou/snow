<?php 

namespace App\Controller;

use Exception;
use App\Entity\Tricks;
use App\Entity\Comments;
use App\Form\TrickFormType;
use App\Form\CommentFormType;
use App\Repository\CommentsRepository;
use App\Services\Interfaces\TricksInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Services\Interfaces\CommentsInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * Permet d'enrégistrer une figure
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
            
            try {
                $this->iTricks->new($trick, $form, $this->getUser());
            } catch (\Throwable $th) {
                return new Exception("Il y a un problème avec le service de création de nouvelle figure");
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

    /**
     * @Route("/trick-{id}-show", name="trick_show", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param Tricks $trick
     * @return Response
     */
    public function showTrick(Request $request, Tricks $trick, CommentsInterface $iComment): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        $comments = $iComment->getComments($trick, $offset);
        
        $comment = new Comments();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $iComment->addComment($comment, $trick, $this->getUser());
            } catch (\Throwable $th) {
                return new Exception("Il y a un problème avec le service de gestion des commentaires");
            }

            $this->addFlash('success', 'Commentaire ajouté avec succès');

            return $this->redirectToRoute('trick_show', ['id' => $trick->getId()]);
        }

        return $this->render('tricks/show.html.twig', [
            'title' => $trick->getName(),
            'images' => $trick->getImages(),
            'trick' => $trick,
            'comments' => $comments,
            'form' => $form->createView(),
            'previous' => $offset - CommentsRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($comments), $offset + CommentsRepository::PAGINATOR_PER_PAGE),
        ]);
    }

    /**
     * @Route("/trick-{id}-update", name="trick_update", methods={"GET", "POST"})
     * @Security("is_granted('ROLE_USER') and user === trick.getUser()")
     *
     * @param Request $request
     * @param Tricks $trick
     * @return Response
     */
    public function updateTrick(Request $request, Tricks $trick): Response
    {
        $form = $this->createForm(TrickFormType::class, $trick, ['status' => 'update']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            try {
                $this->iTricks->update($trick, $form);
            } catch (\Throwable $th) {
                return new Exception("Il y a un problème avec le service de modification des figures");
            }

            $this->addFlash(
                'success', 'La figure a corrèctement été modifié'
            );

            return $this->redirectToRoute('app_home');
        } 

        return $this->render('tricks/update.html.twig', [
            'title' => $trick->getName(),
            'form' => $form->createView(),
            'trick' => $trick
        ]);
    }

    /**
     * @Route("/trick-{id}-delete", name="trick_delete", methods={"POST"})
     * @Security("is_granted('ROLE_USER') and user === trick.getUser()")
     *
     * @param Request $request
     * @param Tricks $trick
     * @return Response
     */
    public function delete(Request $request, Tricks $trick): Response
    {
        $tokenId = (string) 'delete' . $trick->getId();

        if ($this->isCsrfTokenValid($tokenId, (string) $request->request->get('_token'))) {
            try {;
                $this->iTricks->delete($trick);
            } catch (\Throwable $th) {
                return new Exception("Il y a un problème avec le service de suppression des figures");
            }

            $this->addFlash(
                'success',
                'La figure a été correctement supprimée'
            );

            return $this->redirectToRoute('app_home');
        }

        return $this->redirectToRoute('app_home');
    }
}
