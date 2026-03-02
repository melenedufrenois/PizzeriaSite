<?php

namespace App\Controller;

use App\Entity\Pizza;
use App\Entity\Product;
use App\Repository\PizzaRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function home(PizzaRepository $pizzaRepository): Response
    {
        // Get 4 popular pizzas for the home page
        $pizzas = $pizzaRepository->findPopular(4);

        return $this->render('pages/home.html.twig', ['pizzas' => $pizzas]);
    }

    #[Route('/pizzas', name: 'app_pizzas', methods: ['GET'])]
    public function pizzas(): Response
    {
        // Redirect to the new unified menu page
        return $this->redirectToRoute('app_menu', ['category' => 'pizza'], 301);
    }

    #[Route('/carte', name: 'app_menu', methods: ['GET'])]
    public function menu(Request $request, ProductRepository $productRepository): Response
    {
        $category = $request->query->get('category');
        $type = $request->query->get('type');
        $filter = $request->query->get('filter'); // For base (pizza) or alcoholic (drink)
        $sans = $request->query->all('sans'); // Allergen exclusion filters (e.g. sans[]=gluten)
        $regimes = $request->query->all('regime'); // Dietary filters (e.g. regime[]=vegan)

        // Get all active categories
        $categories = Product::getAvailableCategories();
        $activeCategories = $productRepository->findActiveCategories();

        // Get the base product set for the current category (before allergen/diet filters)
        // to compute which filters are relevant
        $filters = [];
        $filterType = null;
        if ($category) {
            $filterData = $productRepository->getFiltersForCategory($category);
            $filters = $filterData['filters'];
            $filterType = $filterData['filterType'];
        }

        // Get base products (with category + sub-filter, but WITHOUT allergen/diet filters)
        // to determine which allergen/diet pills are relevant
        if ($category) {
            if ($filterType === 'base') {
                $baseProducts = $productRepository->findWithFilters($category, null, $filter, null);
            } elseif ($filterType === 'alcoholic') {
                $baseProducts = $productRepository->findWithFilters($category, null, null, $filter);
            } else {
                $baseProducts = $productRepository->findWithFilters($category, $filter, null, null);
            }
        } else {
            $baseProducts = $productRepository->findAllActive();
        }

        // Compute contextual allergens and diets based on current product set
        $availableAllergens = $productRepository->getAllergensFromProducts($baseProducts);

        // Hide dietary filters for everything except pizzas
        $hideDiets = $category !== 'pizza' && $category !== null;
        $availableDiets = $hideDiets ? [] : $productRepository->getRelevantDiets($baseProducts);

        // Build the list of allergens to exclude
        $excludeAllergens = [];
        foreach ($sans as $allergenKey) {
            if (isset($availableAllergens[$allergenKey])) {
                $excludeAllergens[] = $availableAllergens[$allergenKey];
            }
        }

        // Build the list of active diets
        $activeDiets = array_filter($regimes, fn($d) => isset($availableDiets[$d]));

        // Apply allergen and diet filters to get final products
        if ($category) {
            if ($filterType === 'base') {
                $products = $productRepository->findWithFilters($category, null, $filter, null, $excludeAllergens ?: null, $activeDiets ?: null);
            } elseif ($filterType === 'alcoholic') {
                $products = $productRepository->findWithFilters($category, null, null, $filter, $excludeAllergens ?: null, $activeDiets ?: null);
            } else {
                $products = $productRepository->findWithFilters($category, $filter, null, null, $excludeAllergens ?: null, $activeDiets ?: null);
            }
        } else {
            $products = $productRepository->findWithFilters(null, null, null, null, $excludeAllergens ?: null, $activeDiets ?: null);
        }

        // Group products by category for display
        $productsByCategory = [];
        foreach ($products as $product) {
            $cat = $product->getCategory();
            if (!isset($productsByCategory[$cat])) {
                $productsByCategory[$cat] = [];
            }
            $productsByCategory[$cat][] = $product;
        }

        return $this->render('pages/menu.html.twig', [
            'products' => $products,
            'productsByCategory' => $productsByCategory,
            'categories' => $categories,
            'activeCategories' => $activeCategories,
            'filters' => $filters,
            'filterType' => $filterType,
            'activeCategory' => $category,
            'activeFilter' => $filter,
            'availableAllergens' => $availableAllergens,
            'activeSans' => $sans,
            'availableDiets' => $availableDiets,
            'activeRegimes' => $regimes,
        ]);
    }
}
