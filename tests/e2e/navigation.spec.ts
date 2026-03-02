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
    await page.evaluate(() => window.scrollTo(0, 1500));
    // Petit délai pour le scroll
    await page.waitForTimeout(300);
    await expect(page.locator('header')).toBeVisible();
    await expect(page.locator('header')).toBeInViewport();
  });

  test('doit masquer la nav desktop sur mobile et la garder sur desktop', async ({ page }) => {
    // Desktop : nav visible
    await page.setViewportSize({ width: 1280, height: 720 });
    const nav = page.locator('header nav');
    await expect(nav).toBeVisible();

    // Mobile : nav masquée (hidden md:flex)
    await page.setViewportSize({ width: 375, height: 667 });
    await expect(nav).toBeHidden();
  });
});
