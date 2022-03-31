<?php

namespace App\Controller;

use App\Repository\TricksRepository;
use App\Services\Interfaces\HomeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private HomeInterface $iHome;

    public function __construct(HomeInterface $iHome)
    {
        $this->iHome = $iHome;
    }

    /**
     * @Route("/", name="app_home", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        
        $tricks = $this->iHome->home($offset);

        return $this->render('home/index.html.twig', [
            'title' => 'Liste des Figures',
            'tricks' => $tricks,
            'previous' => $offset - TricksRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($tricks), $offset + TricksRepository::PAGINATOR_PER_PAGE),
        ]);
    }
}
