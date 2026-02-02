import { test, expect } from '@playwright/test';

/**
 * Tests E2E pour le menu des pizzas
 */
test.describe('Menu des Pizzas', () => {
  
  test.beforeEach(async ({ page }) => {
    await page.goto('/');
  });

  test('doit afficher la section menu', async ({ page }) => {
    const menuSection = page.locator('#menu');
    await expect(menuSection).toBeVisible();
  });

  test('doit afficher au moins 4 pizzas', async ({ page }) => {
    // Attendre que les pizzas soient chargées
    const pizzaCards = page.locator('#menu .grid > div');
    await expect(pizzaCards).toHaveCount(4);
  });

  test('doit afficher toutes les pizzas avec leurs noms', async ({ page }) => {
    // Vérifier que chaque pizza a un nom
    const pizzaNames = page.locator('#menu h3');
    
    // Vérifier qu'il y a des noms de pizzas
    await expect(pizzaNames).toHaveCount(4);
    
    // Vérifier des pizzas spécifiques
    await expect(page.locator('#menu h3:has-text("Margherita")')).toBeVisible();
    await expect(page.locator('#menu h3:has-text("Grecque")')).toBeVisible();
    await expect(page.locator('#menu h3:has-text("Quatre fromages")')).toBeVisible();
    await expect(page.locator('#menu h3:has-text("Amateur de viande")')).toBeVisible();
  });

  test('doit afficher les images des pizzas', async ({ page }) => {
    // Vérifier que toutes les pizzas ont une image
    const pizzaImages = page.locator('#menu img[alt]');
    await expect(pizzaImages).toHaveCount(4);
    
    // Vérifier qu'au moins une image est visible
    await expect(pizzaImages.first()).toBeVisible();
  });

  test('doit afficher les ingrédients des pizzas', async ({ page }) => {
    // Vérifier la présence d'ingrédients
    const ingredients = page.locator('#menu .text-sm.text-pieza-green\\/70');
    
    // Vérifier qu'il y a des ingrédients affichés
    await expect(ingredients.first()).toBeVisible();
    
    // Vérifier un ingrédient spécifique (Margherita)
    await expect(page.locator('#menu:has-text("Tomate, Mozzarella, Basilic")')).toBeVisible();
  });

  test('doit marquer une pizza comme populaire', async ({ page }) => {
    // Vérifier la présence du badge "Populaire"
    const popularBadge = page.locator('#menu div:has-text("Populaire")').first();
    await expect(popularBadge).toBeVisible();
  });

  test('doit avoir un bouton "Voir toutes les pizzas"', async ({ page }) => {
    const viewAllButton = page.locator('#menu button:has-text("Voir toutes les pizzas")');
    await expect(viewAllButton).toBeVisible();
  });

  test('doit avoir une grille responsive pour les pizzas', async ({ page }) => {
    const grid = page.locator('#menu .grid');
    await expect(grid).toBeVisible();
    
    // Vérifier que la grille a des classes responsive
    await expect(grid).toHaveClass(/grid-cols-1.*md:grid-cols-2.*lg:grid-cols-4/);
  });

  test('doit afficher la description du menu', async ({ page }) => {
    const description = page.locator('#menu p.font-body').first();
    await expect(description).toBeVisible();
    await expect(description).toContainText('pizza');
  });
});
