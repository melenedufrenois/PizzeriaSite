<?php

namespace App\Controller\Admin;

use App\Entity\Drink;
use App\Entity\Dessert;
use App\Entity\Pasta;
use App\Entity\Pizza;
use App\Entity\Product;
use App\Form\Admin\DessertType;
use App\Form\Admin\DrinkType;
use App\Form\Admin\PastaType;
use App\Form\Admin\PizzaType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin', name: 'app_admin_')]
#[IsGranted('ROLE_ADMIN')]
class ProductController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ProductRepository $productRepository,
    ) {}

    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_admin_products');
    }

    #[Route('/products', name: 'products')]
    public function products(Request $request): Response
    {
        $category = $request->query->get('category');

        if ($category) {
            // Filter using specific entity repositories
            $products = match ($category) {
                'pizza' => $this->entityManager->getRepository(Pizza::class)->findBy([], ['displayOrder' => 'ASC', 'name' => 'ASC']),
                'pates' => $this->entityManager->getRepository(Pasta::class)->findBy([], ['displayOrder' => 'ASC', 'name' => 'ASC']),
                'dessert' => $this->entityManager->getRepository(Dessert::class)->findBy([], ['displayOrder' => 'ASC', 'name' => 'ASC']),
                'boisson' => $this->entityManager->getRepository(Drink::class)->findBy([], ['displayOrder' => 'ASC', 'name' => 'ASC']),
                default => $this->productRepository->findBy([], ['displayOrder' => 'ASC', 'name' => 'ASC']),
            };
        } else {
            $products = $this->productRepository->findBy([], ['displayOrder' => 'ASC', 'name' => 'ASC']);
        }

        return $this->render('admin/products/index.html.twig', [
            'products' => $products,
            'category' => $category,
            'categories' => Product::getAvailableCategories(),
        ]);
    }

    #[Route('/product/new', name: 'product_new')]
    public function new(Request $request): Response
    {
        $type = $request->query->get('type', 'pizza');

        $product = match ($type) {
            'pizza' => new Pizza(),
            'pates' => new Pasta(),
            'dessert' => new Dessert(),
            'boisson' => new Drink(),
            default => new Pizza(),
        };

        $formType = match ($type) {
            'pizza' => PizzaType::class,
            'pates' => PastaType::class,
            'dessert' => DessertType::class,
            'boisson' => DrinkType::class,
            default => PizzaType::class,
        };

        $form = $this->createForm($formType, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Set the type based on category
            $product->setType($type);

            $this->entityManager->persist($product);
            $this->entityManager->flush();

            $this->addFlash('success', 'Produit créé avec succès');

            return $this->redirectToRoute('app_admin_products');
        }

        return $this->render('admin/products/new.html.twig', [
            'form' => $form,
            'type' => $type,
            'product' => $product,
        ]);
    }

    #[Route('/product/{id}/edit', name: 'product_edit')]
    public function edit(Request $request, Product $product): Response
    {
        $formType = match (true) {
            $product instanceof Pizza => PizzaType::class,
            $product instanceof Pasta => PastaType::class,
            $product instanceof Dessert => DessertType::class,
            $product instanceof Drink => DrinkType::class,
            default => PizzaType::class,
        };

        $form = $this->createForm($formType, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Produit modifié avec succès');

            return $this->redirectToRoute('app_admin_products');
        }

        return $this->render('admin/products/edit.html.twig', [
            'form' => $form,
            'product' => $product,
        ]);
    }

    #[Route('/product/{id}/toggle', name: 'product_toggle', methods: ['POST'])]
    public function toggle(Product $product): Response
    {
        $product->setActive(!$product->isActive());
        $this->entityManager->flush();

        $status = $product->isActive() ? 'activé' : 'désactivé';
        $this->addFlash('success', sprintf('Produit %s avec succès', $status));

        return $this->redirectToRoute('app_admin_products');
    }

    #[Route('/product/{id}', name: 'product_delete', methods: ['DELETE'])]
    public function delete(Product $product): Response
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();

        $this->addFlash('success', 'Produit supprimé avec succès');

        return $this->redirectToRoute('app_admin_products');
    }
}
