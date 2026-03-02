<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/carte', name: 'menu')]
    public function menu(): Response
    {
        $pizzas = [
            ['name' => 'Margherita', 'desc' => 'Sauce tomate, mozzarella, basilic frais', 'price' => '10,50 €', 'emoji' => '🍕', 'badge' => null],
            ['name' => 'Reine', 'desc' => 'Sauce tomate, jambon, champignons, mozzarella', 'price' => '12,00 €', 'emoji' => '👑', 'badge' => null],
            ['name' => 'Quatre Fromages', 'desc' => 'Mozzarella, gorgonzola, emmental, parmesan', 'price' => '13,50 €', 'emoji' => '🧀', 'badge' => null],
            ['name' => 'Végétarienne', 'desc' => 'Légumes grillés, poivrons, courgettes, mozzarella', 'price' => '12,50 €', 'emoji' => '🥦', 'badge' => 'Végé'],
            ['name' => 'Diavola', 'desc' => 'Sauce tomate, salami piquant, piment, mozzarella', 'price' => '13,00 €', 'emoji' => '🌶️', 'badge' => 'Épicée'],
            ['name' => 'Calzone', 'desc' => 'Pizza pliée, jambon, mozzarella, sauce tomate', 'price' => '13,50 €', 'emoji' => '🫓', 'badge' => null],
        ];

        return $this->render('menu/index.html.twig', ['pizzas' => $pizzas]);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('contact/index.html.twig');
    }
}
