<?php 
namespace App\Services\Interfaces;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface TricksImagesUploaderInterface
{
    public function upload(UploadedFile $file): string;

    public function getTargetPath(): string;
}