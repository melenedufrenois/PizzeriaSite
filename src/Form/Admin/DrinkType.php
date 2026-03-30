<?php

namespace App\Form\Admin;

use App\Entity\Drink;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class DrinkType extends ProductType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('volume', TextType::class, [
                'label' => 'Volume (cl)',
                'required' => false,
                'attr' => [
                    'placeholder' => '33cl, 50cl, 1L',
                ],
            ])
            ->add('isAlcoholic', CheckboxType::class, [
                'label' => 'Alcoolisé',
                'required' => false,
            ]);
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Drink::class,
        ]);
    }
}
