<?php

declare(strict_types=1);

namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ContactRequestData
{
    public const TYPE_RESERVATION = 'reservation';
    public const TYPE_DEVIS = 'devis';
    public const TYPE_QUESTION = 'question';
    public const TYPE_EVENEMENT = 'evenement';

    #[Assert\NotBlank(message: 'Veuillez choisir un type de demande.')]
    #[Assert\Choice(
        choices: [
            self::TYPE_RESERVATION,
            self::TYPE_DEVIS,
            self::TYPE_QUESTION,
            self::TYPE_EVENEMENT,
        ],
        message: 'Type de demande invalide.'
    )]
    public ?string $requestType = null;

    #[Assert\NotBlank(message: 'Le nom est obligatoire.')]
    #[Assert\Length(
        min: 2,
        max: 120,
        minMessage: 'Le nom doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères.'
    )]
    public ?string $fullName = null;

    #[Assert\NotBlank(message: 'L’email est obligatoire.')]
    #[Assert\Email(message: 'Veuillez saisir une adresse email valide.')]
    public ?string $email = null;

    #[Assert\Length(
        max: 25,
        maxMessage: 'Le numéro de téléphone ne peut pas dépasser {{ limit }} caractères.'
    )]
    public ?string $phone = null;

    #[Assert\NotBlank(message: 'Le message est obligatoire.')]
    #[Assert\Length(
        min: 10,
        max: 3000,
        minMessage: 'Le message doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le message ne peut pas dépasser {{ limit }} caractères.'
    )]
    public ?string $message = null;

    /**
     * @return array<string, string|null>
     */
    public function toPayload(): array
    {
        return [
            'requestType' => $this->requestType,
            'fullName' => $this->fullName,
            'email' => $this->email,
            'phone' => $this->phone,
            'message' => $this->message,
        ];
    }
}
