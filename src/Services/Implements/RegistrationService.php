<?php

namespace App\Services\Implements;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\Interfaces\IRegistration;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationService extends AbstractController implements IRegistration
{
    private $hasher;

    private $em;

    private $translator;

    public function __construct(
        UserPasswordHasherInterface $userPasswordHasher, 
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator)
    {
        $this->hasher = $userPasswordHasher;
        $this->em = $entityManager;
        $this->translator = $translator;
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
            // encode the plain password
            $user->setPassword(
            $this->hasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $this->em->persist($user);
            $this->em->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('info@snowtricks.com', '"SnowTrick"'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
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
        
    }
}