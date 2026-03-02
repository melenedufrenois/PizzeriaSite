import { test, expect } from '@playwright/test';

/**
 * Tests E2E — Section Menu (pizzas affichées sur la homepage)
 * Vérifie le contenu dynamique des pizzas chargées depuis la BDD.
 */
test.describe('Menu des Pizzas', () => {

  test.beforeEach(async ({ page }) => {
    await page.goto('/');
  });

  test('doit afficher exactement 4 pizzas avec nom, image et prix', async ({ page }) => {
    const menuSection = page.locator('#menu');
    const cards = menuSection.locator('.grid > div');
    await expect(cards).toHaveCount(4);

    // Chaque carte doit avoir un nom (h3), une image et un prix
    for (let i = 0; i < 4; i++) {
      const card = cards.nth(i);
      await expect(card.locator('h3')).toBeVisible();
      await expect(card.locator('img')).toBeVisible();
      await expect(card.locator('img')).toHaveAttribute('alt', /.+/);
      // Prix au format XX,XX€
      await expect(card.locator('text=/\\d+,\\d+€/')).toBeVisible();
    }
  });

  test('doit afficher les pizzas attendues de la base', async ({ page }) => {
    const menu = page.locator('#menu');

    // Noms réels issus des fixtures
    await expect(menu.locator('h3', { hasText: 'Margherita' })).toBeVisible();
    await expect(menu.locator('h3', { hasText: 'Quatre fromages' })).toBeVisible();
    await expect(menu.locator('h3', { hasText: 'Pepperoni' })).toBeVisible();
    await expect(menu.locator('h3', { hasText: 'Amateur de viande' })).toBeVisible();
  });

  test('doit afficher au moins un badge "Populaire"', async ({ page }) => {
    const badges = page.locator('#menu >> text=Populaire');
    const count = await badges.count();
    expect(count).toBeGreaterThanOrEqual(1);
  });

  test('doit avoir un lien "Voir toutes les pizzas" vers la carte', async ({ page }) => {
    const link = page.locator('#menu a', { hasText: 'Voir toutes les pizzas' });
    await expect(link).toBeVisible();
    await expect(link).toHaveAttribute('href', /\/carte/);
  });

  test('doit avoir une grille responsive', async ({ page }) => {
    const grid = page.locator('#menu .grid');
    await expect(grid).toBeVisible();

    // Vérifier les classes responsive Tailwind
    const classes = await grid.getAttribute('class');
    expect(classes).toContain('grid-cols-1');
    expect(classes).toContain('md:grid-cols-2');
    expect(classes).toContain('lg:grid-cols-4');
  });

  test('doit afficher la description du menu', async ({ page }) => {
    const description = page.locator('#menu p').first();
    await expect(description).toBeVisible();
    await expect(description).toContainText('pizza');
  });
});
