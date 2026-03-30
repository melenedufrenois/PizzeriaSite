<?php

namespace App\Form\Admin;

use App\Entity\Pizza;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class PizzaType extends ProductType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('base', ChoiceType::class, [
                'label' => 'Base',
                'required' => true,
                'placeholder' => 'Sélectionner une base',
                'choices' => Pizza::getAvailableBases(),
            ]);

        // Add ingredients dynamically as a simple text field (comma-separated)
        $builder->add('ingredientsText', TextType::class, [
            'label' => 'Ingrédients (séparés par des virgules)',
            'required' => false,
            'mapped' => false,
            'attr' => [
                'placeholder' => 'Tomate, Mozza, Jambon, Champignons',
            ],
        ]);

        // Pre-populate ingredientsText from the entity
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $pizza = $event->getData();
            $form = $event->getForm();

            if ($pizza instanceof Pizza && $pizza->getIngredients()) {
                $ingredientsText = implode(', ', $pizza->getIngredients());
                $form->get('ingredientsText')->setData($ingredientsText);
            }
        });

        // Save ingredientsText back to the entity on submit
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $pizza = $event->getData();
            $form = $event->getForm();

            if ($pizza instanceof Pizza) {
                $ingredientsText = $form->get('ingredientsText')->getData();
                if ($ingredientsText) {
                    $ingredients = array_map('trim', explode(',', $ingredientsText));
                    $ingredients = array_filter($ingredients);
                    $pizza->setIngredients(array_values($ingredients));
                } else {
                    $pizza->setIngredients([]);
                }
            }
        });
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pizza::class,
        ]);
    }
}
