<?php 
namespace App\Services\DomainCode;

use App\Repository\TricksRepository;
use App\Services\Interfaces\HomeInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeService extends AbstractController implements HomeInterface
{
    private TricksRepository $tricksrepository;

    public function __construct(TricksRepository $tricksrepository)
    {
        $this->tricksrepository = $tricksrepository;
    }

    public function home(int $offset): Paginator
    {
        $paginator = $this->tricksrepository->getPaginationTricks($offset);
        
        return $paginator;
    }
}
