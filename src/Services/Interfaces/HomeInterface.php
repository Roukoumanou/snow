<?php 
namespace App\Services\Interfaces;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;

interface HomeInterface
{
    public function home(int $offset): Paginator;
}
