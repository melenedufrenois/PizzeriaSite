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
                'name' => 'Margarita',
                'image' => 'https://images.pexels.com/photos/2147491/pexels-photo-2147491.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => 18,
            ],
            [
                'id' => 2,
                'name' => 'Greek pizza',
                'image' => 'https://images.pexels.com/photos/803290/pexels-photo-803290.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => 18,
            ],
            [
                'id' => 3,
                'name' => 'Four cheese',
                'image' => 'https://images.pexels.com/photos/7595072/pexels-photo-7595072.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => 18,
            ],
            [
                'id' => 4,
                'name' => 'Meat lover',
                'image' => 'https://images.pexels.com/photos/1146760/pexels-photo-1146760.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => 18,
                'popular' => true,
            ],
        ];

        return $this->render('pages/home.html.twig', ['pizzas' => $pizzas]);
    }
}
