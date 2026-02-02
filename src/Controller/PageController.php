<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function home(): Response
    {
        $pizzas = [
            [
                'id' => 1,
                'name' => 'Margherita',
                'image' => 'https://images.pexels.com/photos/2147491/pexels-photo-2147491.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => 18,
                'ingredients' => ['Tomate', 'Mozzarella', 'Basilic'],
            ],
            [
                'id' => 2,
                'name' => 'Grecque',
                'image' => 'https://images.pexels.com/photos/803290/pexels-photo-803290.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => 18,
                'ingredients' => ['Tomate', 'Mozzarella', 'Olives', 'Feta', 'Oignons'],
            ],
            [
                'id' => 3,
                'name' => 'Quatre fromages',
                'image' => 'https://images.pexels.com/photos/1146760/pexels-photo-1146760.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => 18,
                'ingredients' => ['Mozzarella', 'Gorgonzola', 'Parmesan', 'Chèvre'],
            ],
            [
                'id' => 4,
                'name' => 'Amateur de viande',
                'image' => 'https://images.pexels.com/photos/1146760/pexels-photo-1146760.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => 18,
                'popular' => true,
                'ingredients' => ['Tomate', 'Mozzarella', 'Pepperoni', 'Bacon', 'Saucisse', 'Jambon'],
            ],
        ];

        return $this->render('pages/home.html.twig', ['pizzas' => $pizzas]);
    }
}
