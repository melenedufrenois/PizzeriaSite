<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AppConfigRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AppConfigRepository::class)]
#[ORM\Table(name: 'app_config')]
#[ORM\UniqueConstraint(name: 'uniq_app_config_key', columns: ['config_key'])]
class AppConfig
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'config_key', length: 100)]
    #[Assert\NotBlank(message: 'La clé de configuration est obligatoire')]
    #[Assert\Length(max: 100, maxMessage: 'La clé ne peut pas dépasser {{ limit }} caractères')]
    private string $configKey = '';

    #[ORM\Column(name: 'config_value', length: 255)]
    #[Assert\NotBlank(message: 'La valeur de configuration est obligatoire')]
    #[Assert\Length(max: 255, maxMessage: 'La valeur ne peut pas dépasser {{ limit }} caractères')]
    private string $configValue = '';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConfigKey(): string
    {
        return $this->configKey;
    }

    public function setConfigKey(string $configKey): self
    {
        $this->configKey = $configKey;

        return $this;
    }

    public function getConfigValue(): string
    {
        return $this->configValue;
    }

    public function setConfigValue(string $configValue): self
    {
        $this->configValue = $configValue;

        return $this;
    }
}
