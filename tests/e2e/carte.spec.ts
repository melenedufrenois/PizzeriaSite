import { test, expect } from '@playwright/test';

/**
 * Tests E2E — Page Carte (/carte) avec filtres catégorie et sous-filtres
 * Vérifie le parcours utilisateur complet de navigation dans le menu.
 */
test.describe('Page Carte — Filtres', () => {

  test('doit afficher la page carte avec toutes les catégories', async ({ page }) => {
    await page.goto('/carte');

    await expect(page).toHaveTitle(/La Carte/);
    await expect(page.locator('h1')).toContainText('LA CARTE');

    // 4 sections de catégories visibles (Pizzas, Boissons, Desserts, Pâtes)
    const sections = page.locator('main section');
    await expect(sections).toHaveCount(4);

    // Vérifier les titres de sections
    await expect(page.locator('h2', { hasText: 'Pizzas' })).toBeVisible();
    await expect(page.locator('h2', { hasText: 'Pâtes' })).toBeVisible();
    await expect(page.locator('h2', { hasText: 'Desserts' })).toBeVisible();
    await expect(page.locator('h2', { hasText: 'Boissons' })).toBeVisible();
  });

  test('doit afficher les pills de catégorie avec "Tout" actif par défaut', async ({ page }) => {
    await page.goto('/carte');

    const pills = page.locator('.flex.flex-wrap.justify-center.gap-3 a');
    await expect(pills).toHaveCount(5); // Tout + 4 catégories

    // "Tout" doit être actif (classe bg-pieza-green sans bordure)
    const toutPill = pills.filter({ hasText: 'Tout' });
    const toutClass = await toutPill.getAttribute('class');
    expect(toutClass).toContain('bg-pieza-green');
    expect(toutClass).toContain('text-pieza-cream');
    expect(toutClass).not.toContain('border-2');
  });

  test('doit filtrer par catégorie Pizza — n\'afficher que les pizzas', async ({ page }) => {
    await page.goto('/carte');

    // Cliquer sur le filtre Pizzas
    await page.locator('.flex.flex-wrap.justify-center.gap-3 a', { hasText: 'Pizzas' }).click();

    await expect(page).toHaveURL(/category=pizza/);

    // Une seule section visible
    const sections = page.locator('main section');
    await expect(sections).toHaveCount(1);
    await expect(page.locator('h2', { hasText: 'Pizzas' })).toBeVisible();

    // 12 pizzas affichées
    const cards = page.locator('main section .grid > div');
    await expect(cards).toHaveCount(12);
  });

  test('doit filtrer par catégorie Pâtes — n\'afficher que les pâtes', async ({ page }) => {
    await page.goto('/carte');
    await page.locator('.flex.flex-wrap.justify-center.gap-3 a', { hasText: 'Pâtes' }).click();

    await expect(page).toHaveURL(/category=pates/);
    await expect(page.locator('main section')).toHaveCount(1);
    await expect(page.locator('h2', { hasText: 'Pâtes' })).toBeVisible();

    const cards = page.locator('main section .grid > div');
    await expect(cards).toHaveCount(6);
  });

  test('doit filtrer par catégorie Desserts', async ({ page }) => {
    await page.goto('/carte');
    await page.locator('.flex.flex-wrap.justify-center.gap-3 a', { hasText: 'Desserts' }).click();

    await expect(page).toHaveURL(/category=dessert/);
    await expect(page.locator('main section')).toHaveCount(1);
    await expect(page.locator('main section .grid > div')).toHaveCount(5);
  });

  test('doit filtrer par catégorie Boissons', async ({ page }) => {
    await page.goto('/carte');
    await page.locator('.flex.flex-wrap.justify-center.gap-3 a', { hasText: 'Boissons' }).click();

    await expect(page).toHaveURL(/category=boisson/);
    await expect(page.locator('main section')).toHaveCount(1);
    await expect(page.locator('main section .grid > div')).toHaveCount(14);
  });

  test('doit revenir à "Tout" et afficher toutes les catégories', async ({ page }) => {
    // Aller sur une catégorie filtrée
    await page.goto('/carte?category=pizza');
    await expect(page.locator('main section')).toHaveCount(1);

    // Cliquer sur "Tout" pour revenir
    await page.locator('.flex.flex-wrap.justify-center.gap-3 a', { hasText: 'Tout' }).click();
    await expect(page).toHaveURL(/\/carte$/);
    await expect(page.locator('main section')).toHaveCount(4);
  });
});

test.describe('Page Carte — Sous-filtres', () => {

  test('doit afficher les sous-filtres base pour les pizzas (Tomate / Crème)', async ({ page }) => {
    await page.goto('/carte?category=pizza');

    // Les sous-filtres doivent apparaître
    const subFilters = page.locator('[data-testid="sub-filters"] a');
    await expect(subFilters).toHaveCount(3); // Tous + Tomate + Crème

    await expect(subFilters.filter({ hasText: 'Tous' })).toBeVisible();
    await expect(subFilters.filter({ hasText: 'Tomate' })).toBeVisible();
    await expect(subFilters.filter({ hasText: 'Crème' })).toBeVisible();
  });

  test('doit filtrer les pizzas par base Tomate', async ({ page }) => {
    await page.goto('/carte?category=pizza');
    await page.locator('[data-testid="sub-filters"] a', { hasText: 'Tomate' }).click();

    await expect(page).toHaveURL(/filter=tomate/);
    const cards = page.locator('main section .grid > div');
    await expect(cards).toHaveCount(7);

    // Toutes les cartes doivent afficher le badge "Tomate"
    for (let i = 0; i < 7; i++) {
      await expect(cards.nth(i).locator('text=🍅 Tomate')).toBeVisible();
    }
  });

  test('doit filtrer les pizzas par base Crème', async ({ page }) => {
    await page.goto('/carte?category=pizza');
    await page.locator('[data-testid="sub-filters"] a', { hasText: 'Crème' }).click();

    await expect(page).toHaveURL(/filter=creme/);
    const cards = page.locator('main section .grid > div');
    await expect(cards).toHaveCount(5);

    for (let i = 0; i < 5; i++) {
      await expect(cards.nth(i).locator('text=🥛 Crème')).toBeVisible();
    }
  });

  test('doit afficher les sous-filtres pour les boissons (Softs / Alcools)', async ({ page }) => {
    await page.goto('/carte?category=boisson');

    const subFilters = page.locator('[data-testid="sub-filters"] a');
    await expect(subFilters).toHaveCount(3); // Tous + Softs + Alcools
    await expect(subFilters.filter({ hasText: 'Softs' })).toBeVisible();
    await expect(subFilters.filter({ hasText: 'Alcools' })).toBeVisible();
  });

  test('doit afficher les sous-filtres pour les pâtes', async ({ page }) => {
    await page.goto('/carte?category=pates');

    const subFilters = page.locator('[data-testid="sub-filters"] a');
    await expect(subFilters).toHaveCount(5); // Tous + Viande + Végétarienne + Poisson + Fromage
    await expect(subFilters.filter({ hasText: 'Viande' })).toBeVisible();
    await expect(subFilters.filter({ hasText: 'Végétarienne' })).toBeVisible();
    await expect(subFilters.filter({ hasText: 'Poisson' })).toBeVisible();
    await expect(subFilters.filter({ hasText: 'Fromage' })).toBeVisible();
  });

  test('doit afficher les sous-filtres pour les desserts', async ({ page }) => {
    await page.goto('/carte?category=dessert');

    const subFilters = page.locator('[data-testid="sub-filters"] a');
    await expect(subFilters).toHaveCount(4); // Tous + Classique + Chocolat + Glacé
    await expect(subFilters.filter({ hasText: 'Classique' })).toBeVisible();
    await expect(subFilters.filter({ hasText: 'Chocolat' })).toBeVisible();
    await expect(subFilters.filter({ hasText: 'Glacé' })).toBeVisible();
  });

  test('doit revenir à tous les produits d\'une catégorie via le sous-filtre "Tous"', async ({ page }) => {
    // Aller sur un sous-filtre
    await page.goto('/carte?category=pizza&filter=tomate');
    await expect(page.locator('main section .grid > div')).toHaveCount(7);

    // Cliquer sur "Tous"
    await page.locator('[data-testid="sub-filters"] a', { hasText: 'Tous' }).click();
    await expect(page).toHaveURL(/category=pizza/);
    await expect(page.locator('main section .grid > div')).toHaveCount(12);
  });
});

test.describe('Page Carte — Cartes produit', () => {

  test('chaque produit doit avoir nom, image, prix', async ({ page }) => {
    await page.goto('/carte?category=pizza');

    const cards = page.locator('main section .grid > div');
    const count = await cards.count();
    expect(count).toBeGreaterThan(0);

    // Vérifier les 4 premières cartes (pour la rapidité)
    for (let i = 0; i < Math.min(count, 4); i++) {
      const card = cards.nth(i);
      await expect(card.locator('h3')).toBeVisible();
      await expect(card.locator('img')).toHaveAttribute('alt', /.+/);
      await expect(card.locator('text=/\\d+,\\d+€/')).toBeVisible();
    }
  });

  test('le compteur de produits doit correspondre au nombre de cartes', async ({ page }) => {
    await page.goto('/carte?category=pizza');

    const countText = await page.locator('main section .font-body.text-pieza-green\\/60').textContent();
    const expectedCount = parseInt(countText!.match(/(\d+)/)?.[1] || '0');

    const cards = page.locator('main section .grid > div');
    await expect(cards).toHaveCount(expectedCount);
  });

  test('l\'accès direct via URL avec catégorie fonctionne', async ({ page }) => {
    await page.goto('/carte?category=dessert');

    await expect(page.locator('h2', { hasText: 'Desserts' })).toBeVisible();
    await expect(page.locator('main section')).toHaveCount(1);
    await expect(page.locator('main section .grid > div')).toHaveCount(5);
  });
});
