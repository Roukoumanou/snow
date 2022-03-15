<?php 
namespace App\Services\DomainCode;

use App\Repository\TricksRepository;
use App\Services\Interfaces\HomeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeService extends AbstractController implements HomeInterface
{
    private TricksRepository $tricksrepository;

    public function __construct(TricksRepository $tricksrepository)
    {
        $this->tricksrepository = $tricksrepository;
    }

    public function home(Request $request): Response
    {
        $tricks = $this->tricksrepository->findAll();
        
        return $this->render('home/index.html.twig', [
            'title' => 'Liste des Figures',
            'tricks' => $tricks
        ]);
    }
}