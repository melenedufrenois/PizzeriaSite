<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function home(ProductRepository $productRepository): Response
    {
        // Récupère toutes les pizzas disponibles avec leurs ingrédients
        $pizzas = $productRepository->findAllAvailableWithIngredients();

        return $this->render('pages/home.html.twig', [
            'pizzas' => $pizzas,
        ]);
    }
}
