<?php

namespace App\Services\DomainCode;

use App\Entity\Users;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Services\Interfaces\IMailer;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\Interfaces\IRegistration;
use App\Services\Interfaces\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Interfaces\FileUploaderInterface;
use App\Services\Interfaces\RegistrationInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationService extends AbstractController implements RegistrationInterface
{
    private UserPasswordHasherInterface $hasher;

    private EntityManagerInterface $em;

    private TranslatorInterface $translator;

    private EmailVerifier $emailVerifier;

    private MailerInterface $mailer;

    private FileUploaderInterface $uploader;

    public function __construct(
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator,
        EmailVerifier $emailVerifier,
        MailerInterface $iMailer,
        FileUploaderInterface $uploader
    ) {
        $this->hasher = $userPasswordHasher;
        $this->em = $entityManager;
        $this->translator = $translator;
        $this->emailVerifier = $emailVerifier;
        $this->mailer = $iMailer;
        $this->uploader = $uploader;
    }

    /**
     * Service d'enrégistrement d'un utilisateur
     *
     * @param Request $request
     * @return Response
     */
    public function register(Request $request): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
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

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'title' => 'Inscription',
            'registrationForm' => $form->createView()
        ]);
    }

    /**
     * Service de vérification de l'email d'un utilisateur
     *
     * @param Request $request
     * @return Response
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $this->translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Votre adresse e-mail a été vérifiée.');

        return $this->redirectToRoute('app_home');
    }
}
