<?php 
namespace App\Services\Interfaces;

use Symfony\Component\HttpFoundation\Request;

interface HomeInterface
{
    public function home(Request $request): ?array;
}
