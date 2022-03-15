<?php 

namespace App\Services\Interfaces;

use App\Entity\Tricks;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface TricksInterface
{
    public function new(Request $request): Response;

    public function show(Tricks $trick): Response;

    public function update(Request $request, Tricks $trick): Response;

    public function delete(Tricks $trick): Response;
}