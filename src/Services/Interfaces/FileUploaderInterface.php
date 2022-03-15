<?php 
namespace App\Services\Interfaces;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploaderInterface
{
    public function uploadAvatar(UploadedFile $file): string;

    public function getTargetDirectory(): string;
}
