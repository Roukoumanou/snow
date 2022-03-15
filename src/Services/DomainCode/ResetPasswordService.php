<?php

namespace App\Services\DomainCode;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\Interfaces\ResetPasswordInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordService implements ResetPasswordInterface
{
    private UserPasswordHasherInterface $hasher;

    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $hasher
    ) {
        $this->em = $entityManager;
        $this->hasher = $hasher;
    }

    /**
     * @param Request $request
     * @param string|null $token
     * @return Response
     */
    public function reset(Users $user, Form $form, $token = null): bool
    {
        // Encode(hash) the plain password, and set it.
        $encodedPassword = $this->hasher->hashPassword(
            $user,
            $form->get('plainPassword')->getData()
        );

        $user->setPassword($encodedPassword);
        $this->em->flush();

        return true;
    }

    /**
     * @param string $emailFormData
     * @return null|Users
     */
    public function processSendingPasswordResetEmail(string $emailFormData): ?Users
    {
        return $this->em->getRepository(Users::class)->findOneBy([
            'email' => $emailFormData,
        ]);
    }
}
