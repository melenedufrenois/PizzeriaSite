<?php

namespace App\Form\Admin;

use App\Entity\Pasta;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PastaType extends ProductType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('pastaType', ChoiceType::class, [
                'label' => 'Type de pâtes',
                'required' => false,
                'placeholder' => 'Sélectionner un type',
                'choices' => [
                    'Spaghetti' => 'spaghetti',
                    'Penne' => 'penne',
                    'Fusilli' => 'fusilli',
                    'Farfalle' => 'farfalle',
                    'Ravioli' => 'ravioli',
                    'Tagliatelle' => 'tagliatelle',
                ],
            ]);

        // Add ingredients as text
        $builder->add('ingredientsText', TextType::class, [
            'label' => 'Ingrédients (séparés par des virgules)',
            'required' => false,
            'mapped' => false,
            'attr' => [
                'placeholder' => 'Crème, Lardons, Oignons',
            ],
        ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $pasta = $event->getData();
            $form = $event->getForm();

            if ($pasta instanceof Pasta && $pasta->getIngredients()) {
                $ingredientsText = implode(', ', $pasta->getIngredients());
                $form->get('ingredientsText')->setData($ingredientsText);
            }
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $pasta = $event->getData();
            $form = $event->getForm();

            if ($pasta instanceof Pasta) {
                $ingredientsText = $form->get('ingredientsText')->getData();
                if ($ingredientsText) {
                    $ingredients = array_map('trim', explode(',', $ingredientsText));
                    $ingredients = array_filter($ingredients);
                    $pasta->setIngredients(array_values($ingredients));
                } else {
                    $pasta->setIngredients([]);
                }
            }
        });
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pasta::class,
        ]);
    }
}
