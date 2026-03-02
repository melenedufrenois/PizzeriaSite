<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\AppConfig;
use App\Repository\AppConfigRepository;
use App\Service\Contact\ContactRecipientResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:contact:set-recipient',
    description: 'Définit l’email destinataire des formulaires de contact en base de données.',
)]
class SetContactRecipientCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AppConfigRepository $appConfigRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'Adresse email destinataire');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = trim((string) $input->getArgument('email'));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $io->error('Adresse email invalide.');

            return Command::FAILURE;
        }

        $config = $this->appConfigRepository->findOneByConfigKey(ContactRecipientResolver::CONFIG_KEY);
        if ($config === null) {
            $config = (new AppConfig())->setConfigKey(ContactRecipientResolver::CONFIG_KEY);
            $this->entityManager->persist($config);
        }

        $config->setConfigValue($email);
        $this->entityManager->flush();

        $io->success(sprintf('Destinataire mis à jour: %s', $email));

        return Command::SUCCESS;
    }
}
