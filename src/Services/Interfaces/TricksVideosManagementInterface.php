<?php 
namespace App\Services\Interfaces;

use App\Entity\Videos;
use Symfony\Component\Form\Form;

interface TricksVideosManagementInterface
{
    public function updateVideo(Videos $video, Form $form): bool;

    public function deleteVideo(Videos $video): bool;
}
