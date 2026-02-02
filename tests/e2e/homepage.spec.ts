import { test, expect } from '@playwright/test';

/**
 * Tests E2E pour la page d'accueil de la Pizzeria O'Trari
 */
test.describe('Page d\'accueil', () => {
  
  test.beforeEach(async ({ page }) => {
    await page.goto('/');
  });

  test('doit charger la page d\'accueil correctement', async ({ page }) => {
    // Vérifier que la page se charge
    await expect(page).toHaveTitle(/O'Trari/);
  });

  test('doit afficher le logo O\'Trari', async ({ page }) => {
    // Vérifier la présence du logo dans le header
    const logo = page.locator('header .font-display').first();
    await expect(logo).toHaveText('O\'Trari');
  });

  test('doit avoir un header fixe avec navigation', async ({ page }) => {
    // Vérifier que le header existe et est fixe
    const header = page.locator('header');
    await expect(header).toBeVisible();
    
    // Vérifier la classe fixed
    await expect(header).toHaveClass(/fixed/);
  });

  test('doit avoir tous les liens de navigation principaux', async ({ page }) => {
    // Vérifier les liens de navigation
    const menuLink = page.locator('nav a[href="#menu"]');
    const aboutLink = page.locator('nav a[href="#about"]');
    const contactLink = page.locator('nav a[href="#contact"]');
    
    await expect(menuLink).toBeVisible();
    await expect(aboutLink).toBeVisible();
    await expect(contactLink).toBeVisible();
    
    await expect(menuLink).toHaveText('Menu');
    await expect(aboutLink).toHaveText('À propos');
    await expect(contactLink).toHaveText('Contact');
  });

  test('doit afficher la section hero', async ({ page }) => {
    // Vérifier que la section hero est présente
    const heroSection = page.locator('.min-h-screen').first();
    await expect(heroSection).toBeVisible();
  });

  test('doit afficher la section menu avec le titre', async ({ page }) => {
    // Vérifier la présence de la section menu
    const menuSection = page.locator('#menu');
    await expect(menuSection).toBeVisible();
    
    // Vérifier le titre de la section
    const title = menuSection.locator('h2').first();
    await expect(title).toContainText('LE MEILLEUR');
  });

  test('doit afficher le footer', async ({ page }) => {
    // Vérifier que le footer est présent
    const footer = page.locator('footer, [class*="footer"]').last();
    await expect(footer).toBeVisible();
  });

  test('doit avoir une mise en page responsive', async ({ page }) => {
    // Vérifier sur desktop
    await page.setViewportSize({ width: 1920, height: 1080 });
    await expect(page.locator('header')).toBeVisible();
    
    // Vérifier sur mobile
    await page.setViewportSize({ width: 375, height: 667 });
    await expect(page.locator('header')).toBeVisible();
  });
});
