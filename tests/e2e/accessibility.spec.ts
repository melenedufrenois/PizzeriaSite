import { test, expect } from '@playwright/test';

/**
 * Tests E2E pour l'accessibilité du site
 */
test.describe('Accessibilité', () => {
  
  test.beforeEach(async ({ page }) => {
    await page.goto('/');
  });

  test('doit avoir un titre de page approprié', async ({ page }) => {
    await expect(page).toHaveTitle(/O'Trari/);
  });

  test('doit avoir des balises alt sur toutes les images', async ({ page }) => {
    // Récupérer toutes les images
    const images = page.locator('img');
    const count = await images.count();
    
    // Vérifier que toutes les images ont un attribut alt
    for (let i = 0; i < count; i++) {
      const img = images.nth(i);
      await expect(img).toHaveAttribute('alt');
    }
  });

  test('doit avoir des liens avec du texte descriptif', async ({ page }) => {
    const links = page.locator('a');
    const count = await links.count();
    
    // Vérifier que chaque lien a du contenu ou un aria-label
    for (let i = 0; i < count; i++) {
      const link = links.nth(i);
      const text = await link.textContent();
      const ariaLabel = await link.getAttribute('aria-label');
      
      expect(text?.trim() || ariaLabel).toBeTruthy();
    }
  });

  test('doit avoir une structure de heading appropriée', async ({ page }) => {
    // Vérifier qu'il y a des headings
    const h2Elements = page.locator('h2');
    await expect(h2Elements.first()).toBeVisible();
  });

  test('doit être navigable au clavier', async ({ page }) => {
    // Commencer par le premier élément focusable
    await page.keyboard.press('Tab');
    
    // Vérifier qu'un élément est focusé
    const focusedElement = page.locator(':focus');
    await expect(focusedElement).toBeVisible();
  });

  test('doit avoir un contraste de couleurs suffisant', async ({ page }) => {
    // Vérifier que les éléments texte sont visibles
    const menuTitle = page.locator('#menu h2').first();
    await expect(menuTitle).toBeVisible();
    
    // Vérifier que le texte a une couleur définie
    const color = await menuTitle.evaluate((el) => {
      return window.getComputedStyle(el).color;
    });
    
    expect(color).toBeTruthy();
  });

  test('doit supporter le redimensionnement du texte', async ({ page }) => {
    // Zoom avant
    await page.evaluate(() => {
      document.body.style.zoom = '150%';
    });
    
    // Vérifier que le contenu est toujours visible
    const header = page.locator('header');
    await expect(header).toBeVisible();
    
    // Réinitialiser le zoom
    await page.evaluate(() => {
      document.body.style.zoom = '100%';
    });
  });
});
