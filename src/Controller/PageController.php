<?php

namespace App\Controller;

use App\Repository\IngredientRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/pizza', name: 'app_pizzas', methods: ['GET'])]
    public function pizzas(
        Request $request,
        ProductRepository $productRepository,
        IngredientRepository $ingredientRepository
    ): Response {
        $baseType = $request->query->get('base');
        if (!in_array($baseType, ['tomate', 'creme'], true)) {
            $baseType = null;
        }

        $ingredientIds = $request->query->all('ingredients');
        $ingredientIds = array_values(array_filter($ingredientIds, static function ($id) {
            return is_numeric($id);
        }));
        $ingredientIds = array_map('intval', $ingredientIds);

        $pizzas = $productRepository->findAvailablePizzasWithFilters($baseType, $ingredientIds);
        $ingredients = $ingredientRepository->findAllAvailable();

        return $this->render('pages/pizzas.html.twig', [
            'pizzas' => $pizzas,
            'ingredients' => $ingredients,
            'selectedBase' => $baseType,
            'selectedIngredientIds' => $ingredientIds,
        ]);
    }
}
