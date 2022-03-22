<?php 

namespace App\Services\Interfaces;

use App\Entity\Tricks;
use App\Entity\Users;
use Symfony\Component\Form\Form;

interface TricksInterface
{
    public function new(Tricks $trick, Form $form, Users $user): bool;

    public function update(Tricks $trick, Form $form): bool;

    public function delete(Tricks $trick): bool;
}
