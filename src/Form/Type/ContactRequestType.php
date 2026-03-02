<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Form\Model\ContactRequestData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('requestType', ChoiceType::class, [
                'label' => 'Type de demande',
                'required' => true,
                'placeholder' => 'Sélectionnez votre besoin',
                'choices' => [
                    'Réservation' => ContactRequestData::TYPE_RESERVATION,
                    'Devis' => ContactRequestData::TYPE_DEVIS,
                    'Question' => ContactRequestData::TYPE_QUESTION,
                    'Événement' => ContactRequestData::TYPE_EVENEMENT,
                ],
            ])
            ->add('fullName', TextType::class, [
                'label' => 'Nom complet',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'required' => true,
            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone (optionnel)',
                'required' => false,
                'empty_data' => '',
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message',
                'required' => true,
                'attr' => [
                    'rows' => 6,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactRequestData::class,
        ]);
    }
}
