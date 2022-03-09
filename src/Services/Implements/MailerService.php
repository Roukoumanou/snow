<?php

namespace App\Services\Implements;

use App\Entity\Users;
use App\Security\EmailVerifier;
use Symfony\Component\Mime\Address;
use App\Services\Interfaces\IMailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailerService implements IMailer
{
    private EmailVerifier $emailVerifier;

    private MailerInterface $mailer;

    public function __construct(EmailVerifier $emailVerifier, MailerInterface $mailer)
    {
        $this->emailVerifier = $emailVerifier;
        $this->mailer = $mailer;
    }

    public function sendMail(Users $user): void
    {
        // generate a signed url and email it to the user
        $this->emailVerifier->sendEmailConfirmation(
            'app_verify_email',
            $user,
            (new TemplatedEmail())
                ->from(new Address('info@snowtricks.com', '"SnowTrick"'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );
    }

    public function sendMailForResetPassword(Users $user, $resetToken): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address('no-reply@snowtricks.com', 'Snow Tricks Community'))
            ->to($user->getEmail())
            ->subject('Your password reset request')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
            ]);
        dd($email);
        $this->mailer->send($email);
    }
}
