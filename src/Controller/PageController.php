<?php

namespace App\Controller;

use App\Entity\Pizza;
use App\Repository\PizzaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function home(PizzaRepository $pizzaRepository): Response
    {
        $pizzas = $pizzaRepository->findPopular(4);

        return $this->render('pages/home.html.twig', ['pizzas' => $pizzas]);
    }

    #[Route('/pizzas', name: 'app_pizzas', methods: ['GET'])]
    public function pizzas(Request $request, PizzaRepository $pizzaRepository): Response
    {
        $base = $request->query->get('base');
        $type = $request->query->get('type');
        $ingredient = $request->query->get('ingredient');

        // Get filter options
        $types = $pizzaRepository->findAllTypes();
        $ingredients = $pizzaRepository->findAllIngredients();
        sort($ingredients);

        // If filters are applied, show flat list
        if ($base || $type || $ingredient) {
            $pizzas = $pizzaRepository->findWithFilters($base, $type, $ingredient);
            
            return $this->render('pages/pizzas.html.twig', [
                'pizzas' => $pizzas,
                'types' => $types,
                'ingredients' => $ingredients,
                'activeBase' => $base,
                'activeType' => $type,
                'activeIngredient' => $ingredient,
            ]);
        }

        // No filters - show grouped by base
        $pizzasByBase = $pizzaRepository->findGroupedByBase();

        return $this->render('pages/pizzas.html.twig', [
            'pizzasByBase' => $pizzasByBase,
            'types' => $types,
            'ingredients' => $ingredients,
            'activeBase' => null,
            'activeType' => null,
            'activeIngredient' => null,
        ]);
    }
}
