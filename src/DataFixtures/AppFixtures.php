<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création des ingrédients
        $ingredients = $this->createIngredients($manager);

        // Création des pizzas
        $this->createPizzas($manager, $ingredients);

        $manager->flush();
    }

    private function createIngredients(ObjectManager $manager): array
    {
        $ingredientData = [
            ['name' => 'Tomate', 'price' => '0.50'],
            ['name' => 'Mozzarella', 'price' => '1.00'],
            ['name' => 'Basilic', 'price' => '0.30'],
            ['name' => 'Jambon', 'price' => '1.50'],
            ['name' => 'Champignons', 'price' => '0.80'],
            ['name' => 'Olives', 'price' => '0.60'],
            ['name' => 'Poivrons', 'price' => '0.70'],
            ['name' => 'Oignons', 'price' => '0.40'],
            ['name' => 'Pepperoni', 'price' => '1.80'],
            ['name' => 'Anchois', 'price' => '1.20'],
            ['name' => 'Gorgonzola', 'price' => '1.50'],
            ['name' => 'Parmesan', 'price' => '1.30'],
            ['name' => 'Chèvre', 'price' => '1.40'],
            ['name' => 'Roquette', 'price' => '0.60'],
            ['name' => 'Poulet', 'price' => '2.00'],
            ['name' => 'Bacon', 'price' => '1.60'],
            ['name' => 'Artichauts', 'price' => '1.00'],
            ['name' => 'Saucisse italienne', 'price' => '1.70'],
            ['name' => 'Origan', 'price' => '0.20'],
            ['name' => 'Ail', 'price' => '0.30'],
            ['name' => 'Crème fraîche', 'price' => '0.80'],
            ['name' => 'Emmental', 'price' => '1.00'],
        ];

        $ingredients = [];
        foreach ($ingredientData as $data) {
            $ingredient = new Ingredient();
            $ingredient->setName($data['name']);
            $ingredient->setPrice($data['price']);
            $ingredient->setIsAvailable(true);
            $manager->persist($ingredient);
            $ingredients[$data['name']] = $ingredient;
        }

        return $ingredients;
    }

    private function createPizzas(ObjectManager $manager, array $ingredients): void
    {
        $pizzas = [
            [
                'name' => 'Margherita',
                'description' => 'La classique italienne avec sa sauce tomate, mozzarella fondante et basilic frais. Simple mais parfaite.',
                'price' => '12.00',
                'image' => 'https://images.pexels.com/photos/2147491/pexels-photo-2147491.jpeg?auto=compress&cs=tinysrgb&w=600',
                'category' => 'classique',
                'isPopular' => true,
                'ingredients' => ['Tomate', 'Mozzarella', 'Basilic', 'Origan'],
            ],
            [
                'name' => 'Quatre Fromages',
                'description' => 'Un délice pour les amateurs de fromage : mozzarella, gorgonzola, parmesan et chèvre.',
                'price' => '15.00',
                'image' => 'https://images.pexels.com/photos/7595072/pexels-photo-7595072.jpeg?auto=compress&cs=tinysrgb&w=600',
                'category' => 'classique',
                'isPopular' => true,
                'ingredients' => ['Tomate', 'Mozzarella', 'Gorgonzola', 'Parmesan', 'Chèvre'],
            ],
            [
                'name' => 'Reine',
                'description' => 'La favorite avec jambon blanc, champignons frais et mozzarella sur base tomate.',
                'price' => '14.00',
                'image' => 'https://images.pexels.com/photos/803290/pexels-photo-803290.jpeg?auto=compress&cs=tinysrgb&w=600',
                'category' => 'classique',
                'isPopular' => false,
                'ingredients' => ['Tomate', 'Mozzarella', 'Jambon', 'Champignons'],
            ],
            [
                'name' => 'Pepperoni',
                'description' => 'Généreuse garniture de pepperoni épicé sur mozzarella fondante.',
                'price' => '14.50',
                'image' => 'https://images.pexels.com/photos/1146760/pexels-photo-1146760.jpeg?auto=compress&cs=tinysrgb&w=600',
                'category' => 'viande',
                'isPopular' => true,
                'ingredients' => ['Tomate', 'Mozzarella', 'Pepperoni', 'Origan'],
            ],
            [
                'name' => 'Végétarienne',
                'description' => 'Fraîche et colorée avec poivrons, champignons, olives et oignons.',
                'price' => '13.50',
                'image' => 'https://images.pexels.com/photos/825661/pexels-photo-825661.jpeg?auto=compress&cs=tinysrgb&w=600',
                'category' => 'vegetarienne',
                'isPopular' => false,
                'ingredients' => ['Tomate', 'Mozzarella', 'Poivrons', 'Champignons', 'Olives', 'Oignons'],
            ],
            [
                'name' => 'Calzone',
                'description' => 'Pizza pliée garnie de jambon, mozzarella et champignons. Croustillante à l\'extérieur, fondante à l\'intérieur.',
                'price' => '15.50',
                'image' => 'https://images.pexels.com/photos/4109111/pexels-photo-4109111.jpeg?auto=compress&cs=tinysrgb&w=600',
                'category' => 'specialite',
                'isPopular' => false,
                'ingredients' => ['Tomate', 'Mozzarella', 'Jambon', 'Champignons', 'Origan'],
            ],
            [
                'name' => 'Chicken BBQ',
                'description' => 'Poulet mariné sauce BBQ, oignons rouges et mozzarella. Un goût américain irrésistible.',
                'price' => '16.00',
                'image' => 'https://images.pexels.com/photos/2619967/pexels-photo-2619967.jpeg?auto=compress&cs=tinysrgb&w=600',
                'category' => 'viande',
                'isPopular' => true,
                'ingredients' => ['Tomate', 'Mozzarella', 'Poulet', 'Oignons', 'Bacon'],
            ],
            [
                'name' => 'Napolitaine',
                'description' => 'Anchois, câpres et olives sur base tomate. Un classique méditerranéen.',
                'price' => '14.00',
                'image' => 'https://images.pexels.com/photos/905847/pexels-photo-905847.jpeg?auto=compress&cs=tinysrgb&w=600',
                'category' => 'classique',
                'isPopular' => false,
                'ingredients' => ['Tomate', 'Mozzarella', 'Anchois', 'Olives', 'Origan'],
            ],
            [
                'name' => 'Savoyarde',
                'description' => 'Base crème fraîche, pommes de terre, lardons et reblochon. La montagne dans votre assiette.',
                'price' => '16.50',
                'image' => 'https://images.pexels.com/photos/4394612/pexels-photo-4394612.jpeg?auto=compress&cs=tinysrgb&w=600',
                'category' => 'specialite',
                'isPopular' => false,
                'ingredients' => ['Crème fraîche', 'Mozzarella', 'Emmental', 'Bacon', 'Oignons'],
            ],
            [
                'name' => 'Carnivore',
                'description' => 'Pour les amateurs de viande : pepperoni, bacon, saucisse italienne et poulet.',
                'price' => '18.00',
                'image' => 'https://images.pexels.com/photos/708587/pexels-photo-708587.jpeg?auto=compress&cs=tinysrgb&w=600',
                'category' => 'viande',
                'isPopular' => false,
                'ingredients' => ['Tomate', 'Mozzarella', 'Pepperoni', 'Bacon', 'Saucisse italienne', 'Poulet'],
            ],
            [
                'name' => 'Chèvre Miel',
                'description' => 'Base crème, chèvre fondant, miel et noix. Un accord sucré-salé parfait.',
                'price' => '15.00',
                'image' => 'https://images.pexels.com/photos/1049620/pexels-photo-1049620.jpeg?auto=compress&cs=tinysrgb&w=600',
                'category' => 'specialite',
                'isPopular' => false,
                'ingredients' => ['Crème fraîche', 'Mozzarella', 'Chèvre', 'Roquette'],
            ],
            [
                'name' => 'Hawaïenne',
                'description' => 'Jambon et ananas sur mozzarella. Controversée mais délicieuse !',
                'price' => '14.50',
                'image' => 'https://images.pexels.com/photos/1082343/pexels-photo-1082343.jpeg?auto=compress&cs=tinysrgb&w=600',
                'category' => 'classique',
                'isPopular' => false,
                'ingredients' => ['Tomate', 'Mozzarella', 'Jambon'],
            ],
        ];

        foreach ($pizzas as $data) {
            $product = new Product();
            $product->setName($data['name']);
            $product->setDescription($data['description']);
            $product->setPrice($data['price']);
            $product->setImage($data['image']);
            $product->setCategory($data['category']);
            $product->setIsAvailable(true);
            $product->setIsPopular($data['isPopular']);

            // Ajout des ingrédients
            foreach ($data['ingredients'] as $ingredientName) {
                if (isset($ingredients[$ingredientName])) {
                    $product->addIngredient($ingredients[$ingredientName]);
                }
            }

            $manager->persist($product);
        }
    }
}
