<?php 
namespace App\Services\Interfaces;

use App\Entity\Images;
use Symfony\Component\Form\Form;

interface TricksImagesManagementInterface
{
    public function updateImage(Images $image, Form $form): bool;

    public function deleteImage(Images $image): bool;
}
