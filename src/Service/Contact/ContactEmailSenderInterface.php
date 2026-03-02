<?php

declare(strict_types=1);

namespace App\Service\Contact;

interface ContactEmailSenderInterface
{
    /**
     * @param array<string, string|null> $payload
     */
    public function send(string $recipientEmail, array $payload): void;
}
