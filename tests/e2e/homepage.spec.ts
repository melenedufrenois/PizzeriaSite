import { test, expect } from '@playwright/test';

/**
 * Tests E2E — Page d'accueil
 * Vérifie le chargement, la structure principale et le contenu visible.
 */
test.describe('Page d\'accueil', () => {

  test.beforeEach(async ({ page }) => {
    await page.goto('/');
  });

  test('doit charger avec le bon titre et le logo', async ({ page }) => {
    await expect(page).toHaveTitle(/O'Trari/);

    const logo = page.locator('header a.font-display');
    await expect(logo).toBeVisible();
    await expect(logo).toHaveText("O'Trari");
  });

  test('doit afficher un header sticky avec navigation', async ({ page }) => {
    const header = page.locator('header');
    await expect(header).toBeVisible();
    await expect(header).toHaveCSS('position', 'sticky');

    // Vérifier les 3 liens de navigation (desktop)
    const nav = page.locator('header nav');
    await expect(nav.locator('a', { hasText: 'La Carte' })).toHaveAttribute('href', '/carte');
    await expect(nav.locator('a', { hasText: 'À propos' })).toHaveAttribute('href', '#about');
    await expect(nav.locator('a', { hasText: 'Contact' })).toHaveAttribute('href', '#contact');
  });

  test('doit afficher la section hero avec le titre principal', async ({ page }) => {
    const h1 = page.locator('h1');
    await expect(h1).toBeVisible();
    await expect(h1).toContainText('PIZZA');
  });

  test('doit afficher la section menu avec des pizzas', async ({ page }) => {
    const menuSection = page.locator('#menu');
    await expect(menuSection).toBeVisible();

    const menuTitle = menuSection.locator('h2').first();
    await expect(menuTitle).toContainText('LE MEILLEUR');

    // Exactement 4 cartes de pizza affichées
    const pizzaCards = menuSection.locator('.grid > div');
    await expect(pizzaCards).toHaveCount(4);
  });

  test('doit afficher le footer avec les infos de contact', async ({ page }) => {
    const footer = page.locator('footer#contact');
    await expect(footer).toBeVisible();
    await expect(footer).toContainText("O'Trari");
    await expect(footer).toContainText('Roubaix');
  });

  test('doit être responsive — le header reste visible en mobile', async ({ page }) => {
    await page.setViewportSize({ width: 375, height: 667 });
    await expect(page.locator('header')).toBeVisible();
    await expect(page.locator('h1')).toBeVisible();
  });
});
