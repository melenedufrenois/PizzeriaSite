import { test, expect } from '@playwright/test';

/**
 * Tests E2E — Accessibilité
 * Vérifie les critères d'accessibilité de base (WCAG) sans dépendance à axe-core.
 */
test.describe('Accessibilité', () => {

  test.beforeEach(async ({ page }) => {
    await page.goto('/');
  });

  test('doit avoir un lang="fr" sur la balise html', async ({ page }) => {
    const lang = await page.locator('html').getAttribute('lang');
    expect(lang).toBe('fr');
  });

  test('toutes les images doivent avoir un attribut alt non vide', async ({ page }) => {
    const images = page.locator('img');
    const count = await images.count();
    expect(count).toBeGreaterThan(0);

    for (let i = 0; i < count; i++) {
      const alt = await images.nth(i).getAttribute('alt');
      expect(alt, `Image ${i + 1} n'a pas d'alt`).toBeTruthy();
    }
  });

  test('doit avoir une hiérarchie de headings cohérente (h1 puis h2)', async ({ page }) => {
    // Un seul h1
    const h1Count = await page.locator('h1').count();
    expect(h1Count).toBe(1);

    // Au moins un h2
    const h2Count = await page.locator('h2').count();
    expect(h2Count).toBeGreaterThanOrEqual(1);
  });

  test('les liens doivent avoir du texte accessible', async ({ page }) => {
    // Exclure les liens de la debug toolbar Symfony
    const links = page.locator('a:not(.sf-toolbar-block a):not([class*="sf-"])');
    const count = await links.count();

    for (let i = 0; i < count; i++) {
      const link = links.nth(i);
      const text = (await link.textContent())?.trim();
      const ariaLabel = await link.getAttribute('aria-label');
      const title = await link.getAttribute('title');
      expect(
        text || ariaLabel || title,
        `Lien ${i + 1} n'a pas de texte accessible`
      ).toBeTruthy();
    }
  });

  test('doit être navigable au clavier (focus visible après Tab)', async ({ page }) => {
    await page.keyboard.press('Tab');
    const focused = page.locator(':focus');
    await expect(focused).toBeVisible();
  });

  test('le contenu reste utilisable en zoom 200%', async ({ page }) => {
    await page.setViewportSize({ width: 640, height: 480 });
    await expect(page.locator('header')).toBeVisible();
    await expect(page.locator('#menu')).toBeVisible();
    await expect(page.locator('footer')).toBeVisible();
  });
});
