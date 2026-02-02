<?php

namespace App\DataFixtures;

use App\Entity\Pizza;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PizzaFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $pizzasData = [
            // Base tomate
            [
                'name' => 'Margherita',
                'image' => 'https://images.pexels.com/photos/2147491/pexels-photo-2147491.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '12.00',
                'ingredients' => ['Tomate', 'Mozzarella', 'Basilic'],
                'base' => Pizza::BASE_TOMATE,
                'type' => 'classique',
                'popular' => true,
            ],
            [
                'name' => 'Grecque',
                'image' => 'https://images.pexels.com/photos/803290/pexels-photo-803290.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '14.00',
                'ingredients' => ['Tomate', 'Mozzarella', 'Olives', 'Feta', 'Oignons'],
                'base' => Pizza::BASE_TOMATE,
                'type' => 'classique',
                'popular' => false,
            ],
            [
                'name' => 'Pepperoni',
                'image' => 'https://images.pexels.com/photos/825661/pexels-photo-825661.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '14.50',
                'ingredients' => ['Tomate', 'Mozzarella', 'Pepperoni'],
                'base' => Pizza::BASE_TOMATE,
                'type' => 'viande',
                'popular' => true,
            ],
            [
                'name' => 'Végétarienne',
                'image' => 'https://images.pexels.com/photos/1146760/pexels-photo-1146760.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '13.50',
                'ingredients' => ['Tomate', 'Mozzarella', 'Poivrons', 'Champignons', 'Oignons', 'Olives'],
                'base' => Pizza::BASE_TOMATE,
                'type' => 'végétarienne',
                'popular' => false,
            ],
            [
                'name' => 'Napolitaine',
                'image' => 'https://images.pexels.com/photos/1435907/pexels-photo-1435907.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '13.00',
                'ingredients' => ['Tomate', 'Mozzarella', 'Anchois', 'Câpres', 'Olives'],
                'base' => Pizza::BASE_TOMATE,
                'type' => 'classique',
                'popular' => false,
            ],
            [
                'name' => 'Diavola',
                'image' => 'https://images.pexels.com/photos/4109078/pexels-photo-4109078.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '15.00',
                'ingredients' => ['Tomate', 'Mozzarella', 'Salami piquant', 'Piments'],
                'base' => Pizza::BASE_TOMATE,
                'type' => 'viande',
                'popular' => false,
            ],
            [
                'name' => 'Amateur de viande',
                'image' => 'https://images.pexels.com/photos/1146760/pexels-photo-1146760.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '16.00',
                'ingredients' => ['Tomate', 'Mozzarella', 'Pepperoni', 'Bacon', 'Saucisse', 'Jambon'],
                'base' => Pizza::BASE_TOMATE,
                'type' => 'viande',
                'popular' => true,
            ],
            // Base crème
            [
                'name' => 'Quatre fromages',
                'image' => 'https://images.pexels.com/photos/1146760/pexels-photo-1146760.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '15.00',
                'ingredients' => ['Crème fraîche', 'Mozzarella', 'Gorgonzola', 'Parmesan', 'Chèvre'],
                'base' => Pizza::BASE_CREME,
                'type' => 'fromage',
                'popular' => true,
            ],
            [
                'name' => 'Savoyarde',
                'image' => 'https://images.pexels.com/photos/905847/pexels-photo-905847.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '15.50',
                'ingredients' => ['Crème fraîche', 'Reblochon', 'Lardons', 'Pommes de terre', 'Oignons'],
                'base' => Pizza::BASE_CREME,
                'type' => 'viande',
                'popular' => false,
            ],
            [
                'name' => 'Forestière',
                'image' => 'https://images.pexels.com/photos/1552635/pexels-photo-1552635.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '14.50',
                'ingredients' => ['Crème fraîche', 'Mozzarella', 'Champignons', 'Lardons', 'Persillade'],
                'base' => Pizza::BASE_CREME,
                'type' => 'viande',
                'popular' => false,
            ],
            [
                'name' => 'Saumon',
                'image' => 'https://images.pexels.com/photos/1552635/pexels-photo-1552635.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '16.50',
                'ingredients' => ['Crème fraîche', 'Mozzarella', 'Saumon fumé', 'Aneth', 'Oignons rouges'],
                'base' => Pizza::BASE_CREME,
                'type' => 'poisson',
                'popular' => false,
            ],
            [
                'name' => 'Chèvre Miel',
                'image' => 'https://images.pexels.com/photos/1552635/pexels-photo-1552635.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '14.00',
                'ingredients' => ['Crème fraîche', 'Mozzarella', 'Chèvre', 'Miel', 'Noix'],
                'base' => Pizza::BASE_CREME,
                'type' => 'fromage',
                'popular' => false,
            ],
        ];

        foreach ($pizzasData as $data) {
            $pizza = new Pizza();
            $pizza->setName($data['name']);
            $pizza->setImage($data['image']);
            $pizza->setPrice($data['price']);
            $pizza->setIngredients($data['ingredients']);
            $pizza->setBase($data['base']);
            $pizza->setType($data['type']);
            $pizza->setPopular($data['popular']);
            $pizza->setActive(true);

            $manager->persist($pizza);
        }

        $manager->flush();
    }
}
