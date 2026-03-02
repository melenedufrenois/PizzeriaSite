<?php

declare(strict_types=1);

namespace App\Service\Contact;

use App\Repository\AppConfigRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ContactRecipientResolver
{
    public const CONFIG_KEY = 'contact_recipient_email';

    public function __construct(
        private readonly AppConfigRepository $appConfigRepository,
        #[Autowire('%env(CONTACT_DEFAULT_RECIPIENT)%')]
        private readonly string $defaultRecipient,
    ) {
    }

    public function resolve(): string
    {
        $config = $this->appConfigRepository->findOneByConfigKey(self::CONFIG_KEY);

        if ($config !== null && $config->getConfigValue() !== '') {
            return $config->getConfigValue();
        }

        return $this->defaultRecipient;
    }
}
