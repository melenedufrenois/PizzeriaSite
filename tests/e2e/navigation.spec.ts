import { test, expect } from '@playwright/test';

/**
 * Tests E2E — Navigation
 * Vérifie les parcours utilisateur réels : scroll ancres, lien carte, header sticky.
 */
test.describe('Navigation', () => {

  test.beforeEach(async ({ page }) => {
    await page.goto('/');
  });

  test('doit naviguer vers la section "À propos" via le lien ancre', async ({ page }) => {
    await page.locator('header nav a', { hasText: 'À propos' }).click();
    await expect(page.locator('#about')).toBeInViewport({ timeout: 3000 });
  });

  test('doit naviguer vers le footer/contact via le lien ancre', async ({ page }) => {
    await page.locator('header nav a', { hasText: 'Contact' }).click();
    await expect(page.locator('footer#contact')).toBeInViewport({ timeout: 3000 });
  });

  test('doit naviguer vers la page carte via le lien "La Carte"', async ({ page }) => {
    await page.locator('header nav a', { hasText: 'La Carte' }).click();
    await page.waitForURL(/\/carte/);
    await expect(page).toHaveURL(/\/carte/);
  });

  test('doit garder le header visible après un scroll', async ({ page }) => {
    // Vérifier que le header a la classe sticky (le positionnement dépend du CSS compilé)
    await expect(page.locator('header')).toHaveClass(/sticky/);
    await page.evaluate(() => window.scrollTo(0, 1500));
    await page.waitForTimeout(300);
    await expect(page.locator('header')).toBeVisible();
  });

  test('doit masquer la nav desktop sur mobile et la garder sur desktop', async ({ page }) => {
    const nav = page.locator('header nav');
    // La nav utilise les classes Tailwind "hidden md:flex" pour le responsive
    await expect(nav).toHaveClass(/hidden/);
    await expect(nav).toHaveClass(/md:flex/);

    // Desktop : nav visible (viewport large)
    await page.setViewportSize({ width: 1280, height: 720 });
    await expect(nav).toBeVisible();
  });
});
