<?php

namespace App\Form\Admin;

use App\Entity\Dessert;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class DessertType extends ProductType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('containsAllergens', CheckboxType::class, [
                'label' => 'Contient des allergènes',
                'required' => false,
            ]);

        // Add ingredients as text (required for desserts)
        $builder->add('ingredientsText', TextType::class, [
            'label' => 'Ingrédients (séparés par des virgules)',
            'required' => true,
            'mapped' => false,
            'attr' => [
                'placeholder' => 'Chocolat, Crème, Œufs',
            ],
        ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $dessert = $event->getData();
            $form = $event->getForm();

            if ($dessert instanceof Dessert && $dessert->getIngredients()) {
                $ingredientsText = implode(', ', $dessert->getIngredients());
                $form->get('ingredientsText')->setData($ingredientsText);
            }
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $dessert = $event->getData();
            $form = $event->getForm();

            if ($dessert instanceof Dessert) {
                $ingredientsText = $form->get('ingredientsText')->getData();
                if ($ingredientsText) {
                    $ingredients = array_map('trim', explode(',', $ingredientsText));
                    $ingredients = array_filter($ingredients);
                    $dessert->setIngredients(array_values($ingredients));
                } else {
                    $dessert->setIngredients([]);
                }
            }
        });
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dessert::class,
        ]);
    }
}
