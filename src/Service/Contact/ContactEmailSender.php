<?php

declare(strict_types=1);

namespace App\Service\Contact;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactEmailSender implements ContactEmailSenderInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        #[Autowire('%env(CONTACT_SENDER_EMAIL)%')]
        private readonly string $senderEmail,
    ) {
    }

    /**
     * @param array<string, string|null> $payload
     */
    public function send(string $recipientEmail, array $payload): void
    {
        $requestType = $payload['requestType'] ?? 'demande';
        $subject = sprintf('[Contact Pizzeria] %s - %s', ucfirst((string) $requestType), (string) ($payload['fullName'] ?? 'Client'));
        $replyTo = is_string($payload['email'] ?? null) && trim((string) $payload['email']) !== ''
            ? (string) $payload['email']
            : $this->senderEmail;

        $textBody = implode(PHP_EOL, [
            'Nouvelle demande client',
            '----------------------',
            'Type: '.($payload['requestType'] ?? ''),
            'Nom: '.($payload['fullName'] ?? ''),
            'Email: '.($payload['email'] ?? ''),
            'Téléphone: '.($payload['phone'] ?: 'Non renseigné'),
            '',
            'Message:',
            (string) ($payload['message'] ?? ''),
        ]);

        $email = (new Email())
            ->from($this->senderEmail)
            ->to($recipientEmail)
            ->replyTo($replyTo)
            ->subject($subject)
            ->text($textBody);

        $this->mailer->send($email);
    }
}
