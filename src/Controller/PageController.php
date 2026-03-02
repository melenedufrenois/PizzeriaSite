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

        // Get all active categories
        $categories = Product::getAvailableCategories();
        $activeCategories = $productRepository->findActiveCategories();

        // Get available allergens for filter pills
        $availableAllergens = $productRepository->findAllAllergens();

        // Build the list of allergens to exclude
        $excludeAllergens = [];
        foreach ($sans as $allergenKey) {
            if (isset($availableAllergens[$allergenKey])) {
                $excludeAllergens[] = $availableAllergens[$allergenKey];
            }
        }

        // Get context-specific filters for the selected category
        $filters = [];
        $filterType = null;
        if ($category) {
            $filterData = $productRepository->getFiltersForCategory($category);
            $filters = $filterData['filters'];
            $filterType = $filterData['filterType'];
        }

        // Apply filters based on category
        if ($category) {
            if ($filterType === 'base') {
                $products = $productRepository->findWithFilters($category, null, $filter, null, $excludeAllergens ?: null);
            } elseif ($filterType === 'alcoholic') {
                $products = $productRepository->findWithFilters($category, null, null, $filter, $excludeAllergens ?: null);
            } else {
                $products = $productRepository->findWithFilters($category, $filter, null, null, $excludeAllergens ?: null);
            }
        } else {
            $products = $productRepository->findWithFilters(null, null, null, null, $excludeAllergens ?: null);
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
        ]);
    }
}
