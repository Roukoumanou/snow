<?php 
namespace App\Services\Interfaces;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface HomeInterface
{
    public function home(Request $request): ?array;
}
