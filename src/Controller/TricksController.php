<?php 

namespace App\Controller;

use App\Entity\Tricks;
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
        return $this->iTricks->new($request);
    }

    /**
     * @Route("/trick-{id}-show", name="trick_show", methods={"GET", "POST"})
     *
     * @param Tricks $trick
     * @return Response
     */
    public function showTrick(Tricks $trick): Response
    {
        return $this->iTricks->show($trick);
    }

    public function updateTrick(Request $request, Tricks $trick): Response
    {
        return $this->iTricks->update($request, $trick);
    }

    public function delete(Tricks $trick): Response
    {
        return $this->iTricks->delete($trick);
    }
}