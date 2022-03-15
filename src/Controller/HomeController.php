<?php

namespace App\Controller;

use App\Services\Interfaces\HomeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $tricks = $this->iHome->home($request);

        return $this->render('home/index.html.twig', [
            'title' => 'Liste des Figures',
            'tricks' => $tricks
        ]);
    }
}
