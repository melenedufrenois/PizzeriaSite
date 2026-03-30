<?php

namespace App\Form\Admin;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description / Ingrédients',
                'required' => false,
                'attr' => [
                    'rows' => 4,
                ],
            ])
            ->add('image', UrlType::class, [
                'label' => 'URL de l\'image',
                'required' => true,
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix (€)',
                'required' => true,
                'scale' => 2,
            ])
            ->add('type', TextType::class, [
                'label' => 'Catégorie',
                'required' => true,
                'help' => 'pizza, pates, dessert, boisson',
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'Disponible',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
            ->add('popular', CheckboxType::class, [
                'label' => 'Populaire',
                'required' => false,
            ])
            ->add('displayOrder', IntegerType::class, [
                'label' => 'Ordre d\'affichage',
                'required' => false,
                'attr' => [
                    'min' => 0,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
