<?php

namespace App\DataFixtures;

use App\Entity\Dessert;
use App\Entity\Drink;
use App\Entity\Pasta;
use App\Entity\Pizza;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadPizzas($manager);
        $this->loadPastas($manager);
        $this->loadDesserts($manager);
        $this->loadDrinks($manager);

        $manager->flush();
    }

    private function loadPizzas(ObjectManager $manager): void
    {
        $pizzasData = [
            // Base tomate
            [
                'name' => 'Margherita',
                'description' => 'La classique italienne avec sa sauce tomate, mozzarella fondante et basilic frais.',
                'image' => 'https://images.pexels.com/photos/2147491/pexels-photo-2147491.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '12.00',
                'ingredients' => ['Tomate', 'Mozzarella', 'Basilic'],
                'base' => Pizza::BASE_TOMATE,
                'type' => 'classique',
                'popular' => true,
            ],
            [
                'name' => 'Grecque',
                'description' => 'Une pizza méditerranéenne avec feta crémeuse et olives kalamata.',
                'image' => 'https://images.pexels.com/photos/803290/pexels-photo-803290.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '14.00',
                'ingredients' => ['Tomate', 'Mozzarella', 'Olives', 'Feta', 'Oignons'],
                'base' => Pizza::BASE_TOMATE,
                'type' => 'classique',
                'popular' => false,
            ],
            [
                'name' => 'Pepperoni',
                'description' => 'Un classique américain avec du pepperoni épicé et croustillant.',
                'image' => 'https://images.pexels.com/photos/825661/pexels-photo-825661.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '14.50',
                'ingredients' => ['Tomate', 'Mozzarella', 'Pepperoni'],
                'base' => Pizza::BASE_TOMATE,
                'type' => 'viande',
                'popular' => true,
            ],
            [
                'name' => 'Végétarienne',
                'description' => 'Un festival de légumes frais pour les amateurs de verdure.',
                'image' => 'https://images.pexels.com/photos/1146760/pexels-photo-1146760.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '13.50',
                'ingredients' => ['Tomate', 'Mozzarella', 'Poivrons', 'Champignons', 'Oignons', 'Olives'],
                'base' => Pizza::BASE_TOMATE,
                'type' => 'végétarienne',
                'popular' => false,
            ],
            [
                'name' => 'Napolitaine',
                'description' => 'La tradition napolitaine avec anchois et câpres.',
                'image' => 'https://images.pexels.com/photos/1435907/pexels-photo-1435907.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '13.00',
                'ingredients' => ['Tomate', 'Mozzarella', 'Anchois', 'Câpres', 'Olives'],
                'base' => Pizza::BASE_TOMATE,
                'type' => 'classique',
                'popular' => false,
            ],
            [
                'name' => 'Diavola',
                'description' => 'Pour les amateurs de sensations fortes avec son salami piquant.',
                'image' => 'https://images.pexels.com/photos/4109078/pexels-photo-4109078.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '15.00',
                'ingredients' => ['Tomate', 'Mozzarella', 'Salami piquant', 'Piments'],
                'base' => Pizza::BASE_TOMATE,
                'type' => 'viande',
                'popular' => false,
            ],
            [
                'name' => 'Amateur de viande',
                'description' => 'Un festin de viandes pour les carnivores assumés.',
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
                'description' => 'Un quatuor de fromages fondants sur une base crémeuse.',
                'image' => 'https://images.pexels.com/photos/1146760/pexels-photo-1146760.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '15.00',
                'ingredients' => ['Crème fraîche', 'Mozzarella', 'Gorgonzola', 'Parmesan', 'Chèvre'],
                'base' => Pizza::BASE_CREME,
                'type' => 'fromage',
                'popular' => true,
            ],
            [
                'name' => 'Savoyarde',
                'description' => 'Les saveurs des Alpes avec reblochon et pommes de terre.',
                'image' => 'https://images.pexels.com/photos/905847/pexels-photo-905847.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '15.50',
                'ingredients' => ['Crème fraîche', 'Reblochon', 'Lardons', 'Pommes de terre', 'Oignons'],
                'base' => Pizza::BASE_CREME,
                'type' => 'viande',
                'popular' => false,
            ],
            [
                'name' => 'Forestière',
                'description' => 'Un voyage en forêt avec ses champignons et lardons.',
                'image' => 'https://images.pexels.com/photos/1552635/pexels-photo-1552635.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '14.50',
                'ingredients' => ['Crème fraîche', 'Mozzarella', 'Champignons', 'Lardons', 'Persillade'],
                'base' => Pizza::BASE_CREME,
                'type' => 'viande',
                'popular' => false,
            ],
            [
                'name' => 'Saumon',
                'description' => 'Fraîcheur marine avec saumon fumé et aneth.',
                'image' => 'https://images.pexels.com/photos/1552635/pexels-photo-1552635.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '16.50',
                'ingredients' => ['Crème fraîche', 'Mozzarella', 'Saumon fumé', 'Aneth', 'Oignons rouges'],
                'base' => Pizza::BASE_CREME,
                'type' => 'poisson',
                'popular' => false,
            ],
            [
                'name' => 'Chèvre Miel',
                'description' => 'Alliance sucrée-salée avec chèvre crémeux et miel doré.',
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
            $pizza->setDescription($data['description']);
            $pizza->setImage($data['image']);
            $pizza->setPrice($data['price']);
            $pizza->setIngredients($data['ingredients']);
            $pizza->setBase($data['base']);
            $pizza->setType($data['type']);
            $pizza->setPopular($data['popular']);
            $pizza->setActive(true);

            $manager->persist($pizza);
        }
    }

    private function loadPastas(ObjectManager $manager): void
    {
        $pastasData = [
            [
                'name' => 'Spaghetti Bolognaise',
                'description' => 'Les authentiques spaghetti à la sauce bolognaise mijotée pendant des heures.',
                'image' => 'https://images.pexels.com/photos/1437267/pexels-photo-1437267.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '12.00',
                'ingredients' => ['Spaghetti', 'Bœuf haché', 'Tomate', 'Oignons', 'Carottes', 'Parmesan'],
                'pastaType' => 'spaghetti',
                'type' => 'viande',
                'popular' => true,
            ],
            [
                'name' => 'Carbonara',
                'description' => 'La vraie recette romaine avec guanciale et pecorino.',
                'image' => 'https://images.pexels.com/photos/4518843/pexels-photo-4518843.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '13.00',
                'ingredients' => ['Spaghetti', 'Guanciale', 'Œufs', 'Pecorino', 'Poivre noir'],
                'pastaType' => 'spaghetti',
                'type' => 'viande',
                'popular' => true,
            ],
            [
                'name' => 'Penne Arrabiata',
                'description' => 'Des pennes épicées pour les amateurs de piquant.',
                'image' => 'https://images.pexels.com/photos/1527603/pexels-photo-1527603.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '10.50',
                'ingredients' => ['Penne', 'Tomate', 'Ail', 'Piment', 'Persil'],
                'pastaType' => 'penne',
                'type' => 'végétarienne',
                'popular' => false,
            ],
            [
                'name' => 'Tagliatelles au Saumon',
                'description' => 'Tagliatelles fraîches avec saumon dans une sauce crémeuse.',
                'image' => 'https://images.pexels.com/photos/1279330/pexels-photo-1279330.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '14.50',
                'ingredients' => ['Tagliatelles', 'Saumon', 'Crème fraîche', 'Aneth', 'Citron'],
                'pastaType' => 'tagliatelles',
                'type' => 'poisson',
                'popular' => false,
            ],
            [
                'name' => 'Lasagnes Maison',
                'description' => 'Couches de pâtes, viande et béchamel gratinées au four.',
                'image' => 'https://images.pexels.com/photos/5949893/pexels-photo-5949893.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '13.50',
                'ingredients' => ['Lasagnes', 'Bœuf', 'Tomate', 'Béchamel', 'Parmesan'],
                'pastaType' => 'lasagnes',
                'type' => 'viande',
                'popular' => true,
            ],
            [
                'name' => 'Gnocchi 4 Fromages',
                'description' => 'Gnocchi moelleux nappés de quatre fromages fondants.',
                'image' => 'https://images.pexels.com/photos/5949886/pexels-photo-5949886.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '12.50',
                'ingredients' => ['Gnocchi', 'Gorgonzola', 'Parmesan', 'Mozzarella', 'Fontina'],
                'pastaType' => 'gnocchi',
                'type' => 'fromage',
                'popular' => false,
            ],
        ];

        foreach ($pastasData as $data) {
            $pasta = new Pasta();
            $pasta->setName($data['name']);
            $pasta->setDescription($data['description']);
            $pasta->setImage($data['image']);
            $pasta->setPrice($data['price']);
            $pasta->setIngredients($data['ingredients']);
            $pasta->setPastaType($data['pastaType']);
            $pasta->setType($data['type']);
            $pasta->setPopular($data['popular']);
            $pasta->setActive(true);

            $manager->persist($pasta);
        }
    }

    private function loadDesserts(ObjectManager $manager): void
    {
        $dessertsData = [
            [
                'name' => 'Tiramisu',
                'description' => 'Le classique italien avec mascarpone et café.',
                'image' => 'https://images.pexels.com/photos/6880219/pexels-photo-6880219.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '6.50',
                'ingredients' => ['Mascarpone', 'Café', 'Biscuits', 'Cacao'],
                'type' => 'classique',
                'popular' => true,
                'containsAllergens' => true,
            ],
            [
                'name' => 'Panna Cotta',
                'description' => 'Crème onctueuse à la vanille avec coulis de fruits rouges.',
                'image' => 'https://images.pexels.com/photos/5848509/pexels-photo-5848509.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '5.50',
                'ingredients' => ['Crème', 'Vanille', 'Fruits rouges'],
                'type' => 'classique',
                'popular' => true,
                'containsAllergens' => true,
            ],
            [
                'name' => 'Fondant au Chocolat',
                'description' => 'Cœur coulant au chocolat noir intense.',
                'image' => 'https://images.pexels.com/photos/4110008/pexels-photo-4110008.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '7.00',
                'ingredients' => ['Chocolat noir', 'Beurre', 'Œufs', 'Sucre'],
                'type' => 'chocolat',
                'popular' => false,
                'containsAllergens' => true,
            ],
            [
                'name' => 'Gelato Artisanal',
                'description' => 'Glace italienne artisanale, 2 boules au choix.',
                'image' => 'https://images.pexels.com/photos/1362534/pexels-photo-1362534.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '4.50',
                'ingredients' => ['Lait', 'Crème', 'Fruits frais'],
                'type' => 'glacé',
                'popular' => false,
                'containsAllergens' => true,
            ],
            [
                'name' => 'Cannoli Siciliens',
                'description' => 'Tubes croustillants farcis de ricotta sucrée.',
                'image' => 'https://images.pexels.com/photos/5848509/pexels-photo-5848509.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '5.00',
                'ingredients' => ['Ricotta', 'Sucre', 'Pistaches', 'Pépites chocolat'],
                'type' => 'classique',
                'popular' => false,
                'containsAllergens' => true,
            ],
        ];

        foreach ($dessertsData as $data) {
            $dessert = new Dessert();
            $dessert->setName($data['name']);
            $dessert->setDescription($data['description']);
            $dessert->setImage($data['image']);
            $dessert->setPrice($data['price']);
            $dessert->setIngredients($data['ingredients']);
            $dessert->setType($data['type']);
            $dessert->setPopular($data['popular']);
            $dessert->setContainsAllergens($data['containsAllergens']);
            $dessert->setActive(true);

            $manager->persist($dessert);
        }
    }

    private function loadDrinks(ObjectManager $manager): void
    {
        $drinksData = [
            [
                'name' => 'Coca-Cola',
                'description' => 'Le célèbre soda américain.',
                'image' => 'https://images.pexels.com/photos/2983100/pexels-photo-2983100.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '2.50',
                'volume' => '33cl',
                'type' => 'soda',
                'popular' => true,
                'isAlcoholic' => false,
            ],
            [
                'name' => 'Eau Minérale',
                'description' => 'Eau de source naturelle.',
                'image' => 'https://images.pexels.com/photos/416528/pexels-photo-416528.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '2.00',
                'volume' => '50cl',
                'type' => 'eau',
                'popular' => false,
                'isAlcoholic' => false,
            ],
            [
                'name' => 'Eau Pétillante',
                'description' => 'San Pellegrino.',
                'image' => 'https://images.pexels.com/photos/1000084/pexels-photo-1000084.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '2.50',
                'volume' => '50cl',
                'type' => 'eau',
                'popular' => false,
                'isAlcoholic' => false,
            ],
            [
                'name' => 'Limonade Maison',
                'description' => 'Citrons pressés et menthe fraîche.',
                'image' => 'https://images.pexels.com/photos/2109099/pexels-photo-2109099.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '3.50',
                'volume' => '33cl',
                'type' => 'maison',
                'popular' => true,
                'isAlcoholic' => false,
            ],
            [
                'name' => 'Orangina',
                'description' => 'Boisson gazeuse à l\'orange avec pulpe.',
                'image' => 'https://images.pexels.com/photos/2983100/pexels-photo-2983100.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '2.50',
                'volume' => '33cl',
                'type' => 'soda',
                'popular' => false,
                'isAlcoholic' => false,
            ],
            [
                'name' => 'Sprite',
                'description' => 'Soda citron-lime rafraîchissant.',
                'image' => 'https://images.pexels.com/photos/2983100/pexels-photo-2983100.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '2.50',
                'volume' => '33cl',
                'type' => 'soda',
                'popular' => false,
                'isAlcoholic' => false,
            ],
            [
                'name' => 'Thé Glacé Pêche',
                'description' => 'Thé infusé avec des arômes de pêche.',
                'image' => 'https://images.pexels.com/photos/792613/pexels-photo-792613.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '3.00',
                'volume' => '50cl',
                'type' => 'thé',
                'popular' => false,
                'isAlcoholic' => false,
            ],
            [
                'name' => 'Café Espresso',
                'description' => 'Café italien serré et intense.',
                'image' => 'https://images.pexels.com/photos/312418/pexels-photo-312418.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '1.80',
                'volume' => null,
                'type' => 'café',
                'popular' => true,
                'isAlcoholic' => false,
            ],
            // Boissons alcoolisées
            [
                'name' => 'Bière Moretti',
                'description' => 'Bière blonde italienne légère et rafraîchissante.',
                'image' => 'https://images.pexels.com/photos/1552630/pexels-photo-1552630.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '4.50',
                'volume' => '33cl',
                'type' => 'bière',
                'popular' => true,
                'isAlcoholic' => true,
            ],
            [
                'name' => 'Bière Peroni',
                'description' => 'Bière blonde premium italienne.',
                'image' => 'https://images.pexels.com/photos/1552630/pexels-photo-1552630.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '4.50',
                'volume' => '33cl',
                'type' => 'bière',
                'popular' => false,
                'isAlcoholic' => true,
            ],
            [
                'name' => 'Vin Rouge Chianti',
                'description' => 'Vin rouge toscan aux arômes de cerise et d\'épices.',
                'image' => 'https://images.pexels.com/photos/2702805/pexels-photo-2702805.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '5.00',
                'volume' => '15cl',
                'type' => 'vin',
                'popular' => true,
                'isAlcoholic' => true,
            ],
            [
                'name' => 'Vin Blanc Pinot Grigio',
                'description' => 'Vin blanc sec et fruité du nord de l\'Italie.',
                'image' => 'https://images.pexels.com/photos/2702805/pexels-photo-2702805.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '5.00',
                'volume' => '15cl',
                'type' => 'vin',
                'popular' => false,
                'isAlcoholic' => true,
            ],
            [
                'name' => 'Limoncello',
                'description' => 'Liqueur de citron de la côte amalfitaine, servie glacée.',
                'image' => 'https://images.pexels.com/photos/4498176/pexels-photo-4498176.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '4.00',
                'volume' => '4cl',
                'type' => 'digestif',
                'popular' => true,
                'isAlcoholic' => true,
            ],
            [
                'name' => 'Aperol Spritz',
                'description' => 'Cocktail italien iconique : Aperol, Prosecco et eau gazeuse.',
                'image' => 'https://images.pexels.com/photos/4498176/pexels-photo-4498176.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '7.00',
                'volume' => '20cl',
                'type' => 'cocktail',
                'popular' => true,
                'isAlcoholic' => true,
            ],
        ];

        foreach ($drinksData as $data) {
            $drink = new Drink();
            $drink->setName($data['name']);
            $drink->setDescription($data['description']);
            $drink->setImage($data['image']);
            $drink->setPrice($data['price']);
            $drink->setVolume($data['volume']);
            $drink->setType($data['type']);
            $drink->setPopular($data['popular']);
            $drink->setIsAlcoholic($data['isAlcoholic']);
            $drink->setActive(true);

            $manager->persist($drink);
        }
    }
}
