import { test, expect } from '@playwright/test';

/**
 * Tests E2E pour la navigation du site
 */
test.describe('Navigation', () => {
  
  test.beforeEach(async ({ page }) => {
    await page.goto('/');
  });

  test('doit naviguer vers la section menu en cliquant sur le lien', async ({ page }) => {
    // Cliquer sur le lien Menu
    await page.click('nav a[href="#menu"]');
    
    // Attendre un peu que le scroll se fasse
    await page.waitForTimeout(500);
    
    // Vérifier que la section menu est visible dans le viewport
    const menuSection = page.locator('#menu');
    await expect(menuSection).toBeInViewport();
  });

  test('doit naviguer vers la section à propos en cliquant sur le lien', async ({ page }) => {
    // Cliquer sur le lien À propos
    await page.click('nav a[href="#about"]');
    
    // Attendre un peu que le scroll se fasse
    await page.waitForTimeout(500);
    
    // Vérifier que la section about est visible dans le viewport
    const aboutSection = page.locator('#about');
    await expect(aboutSection).toBeInViewport();
  });

  test('doit naviguer vers la section contact en cliquant sur le lien', async ({ page }) => {
    // Cliquer sur le lien Contact
    await page.click('nav a[href="#contact"]');
    
    // Attendre un peu que le scroll se fasse
    await page.waitForTimeout(500);
    
    // Vérifier que la section contact est visible
    const contactSection = page.locator('#contact');
    await expect(contactSection).toBeInViewport();
  });

  test('doit garder le header visible lors du scroll', async ({ page }) => {
    // Scroller vers le bas
    await page.evaluate(() => window.scrollTo(0, 1000));
    await page.waitForTimeout(300);
    
    // Vérifier que le header est toujours visible
    const header = page.locator('header');
    await expect(header).toBeVisible();
    await expect(header).toBeInViewport();
  });

  test('doit avoir des liens de navigation avec effet hover', async ({ page }) => {
    const menuLink = page.locator('nav a[href="#menu"]');
    
    // Vérifier que le lien existe
    await expect(menuLink).toBeVisible();
    
    // Vérifier la classe hover
    await expect(menuLink).toHaveClass(/hover:text-pieza-orange/);
  });

  test('doit afficher le logo cliquable', async ({ page }) => {
    const logo = page.locator('header .font-display').first();
    await expect(logo).toBeVisible();
    await expect(logo).toHaveText('O\'Trari');
  });

  test('doit masquer la navigation mobile sur desktop', async ({ page }) => {
    // Définir une taille de viewport desktop
    await page.setViewportSize({ width: 1920, height: 1080 });
    
    // Vérifier que la navigation est visible
    const nav = page.locator('nav');
    await expect(nav).toBeVisible();
    
    // Vérifier qu'elle a la classe hidden pour mobile
    await expect(nav).toHaveClass(/hidden.*md:flex/);
  });
});
