<?php

namespace App\Services\DomainCode;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\Interfaces\MailerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Interfaces\FileUploaderInterface;
use App\Services\Interfaces\RegistrationInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Form;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationService implements RegistrationInterface
{
    private UserPasswordHasherInterface $hasher;

    private EntityManagerInterface $em;

    private MailerInterface $mailer;

    private FileUploaderInterface $uploader;

    public function __construct(
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        MailerInterface $iMailer,
        FileUploaderInterface $uploader
    ) {
        $this->hasher = $userPasswordHasher;
        $this->em = $entityManager;
        $this->mailer = $iMailer;
        $this->uploader = $uploader;
    }

    /**
     * Service d'enrégistrement d'un utilisateur
     *
     * @param Form $form
     * @param Users $user
     * @return boolean
     */
    public function register(Form $form, Users $user): bool
    {
        /** @var UploadedFile $avatar */
        $avatar = $form->get('avatar')->getData();
        $avatar = $this->uploader->uploadAvatar($avatar);

        // encode the plain password
        $user->setPassword(
            $this->hasher->hashPassword(
                $user,
                $form->get('password')->getData()
            )
        )->setAvatar($avatar);

        $this->em->persist($user);
        $this->em->flush();

        $this->mailer->sendMail($user);

        return true;
    }
}
