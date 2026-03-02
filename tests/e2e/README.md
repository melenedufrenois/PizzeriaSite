# Guide des Tests Playwright

## 📖 À propos

Ce fichier contient des exemples et des bonnes pratiques pour écrire des tests Playwright pour le site de la Pizzeria O'Trari.

## 🎯 Structure d'un test

```typescript
import { test, expect } from '@playwright/test';

test.describe('Groupe de tests', () => {
  
  test.beforeEach(async ({ page }) => {
    // Code exécuté avant chaque test
    await page.goto('/');
  });

  test('nom du test', async ({ page }) => {
    // Le code du test
    const element = page.locator('selector');
    await expect(element).toBeVisible();
  });
});
```

## 🔍 Sélecteurs courants

```typescript
// Par ID
page.locator('#menu')

// Par classe CSS
page.locator('.pizza-card')

// Par texte
page.locator('text=O\'Trari')
page.getByText('Menu')

// Par rôle
page.getByRole('button', { name: 'Commander' })

// Par attribut
page.locator('[data-testid="pizza-item"]')

// Combiner des sélecteurs
page.locator('#menu .pizza-card').first()
page.locator('nav a[href="#menu"]')
```

## ✅ Assertions courantes

```typescript
// Visibilité
await expect(element).toBeVisible()
await expect(element).toBeHidden()

// Texte
await expect(element).toHaveText('Texte exact')
await expect(element).toContainText('Partie du texte')

// Attributs
await expect(element).toHaveAttribute('href', '/url')
await expect(element).toHaveClass(/ma-classe/)

// Nombre d'éléments
await expect(elements).toHaveCount(4)

// État
await expect(checkbox).toBeChecked()
await expect(button).toBeDisabled()
await expect(button).toBeEnabled()

// URL
await expect(page).toHaveURL('http://localhost/')
await expect(page).toHaveTitle(/Titre/)
```

## 🎭 Actions courantes

```typescript
// Navigation
await page.goto('/')
await page.goBack()
await page.reload()

// Clics
await page.click('button')
await page.locator('a').click()

// Saisie de texte
await page.fill('input[name="email"]', 'test@example.com')
await page.type('input', 'Texte à taper')

// Sélection
await page.selectOption('select', 'option-value')

// Hover
await page.hover('.menu-item')

// Scroll
await page.evaluate(() => window.scrollTo(0, 1000))

// Attente
await page.waitForSelector('.loaded')
await page.waitForLoadState('networkidle')
// Éviter waitForTimeout() - préférer les assertions avec timeout
```

## 📝 Exemples de tests spécifiques

### Test de formulaire

```typescript
test('doit soumettre le formulaire de contact', async ({ page }) => {
  await page.goto('/');
  
  // Remplir le formulaire
  await page.fill('input[name="name"]', 'Jean Dupont');
  await page.fill('input[name="email"]', 'jean@example.com');
  await page.fill('textarea[name="message"]', 'Bonjour!');
  
  // Soumettre
  await page.click('button[type="submit"]');
  
  // Vérifier le message de succès
  await expect(page.locator('.success-message')).toBeVisible();
});
```

### Test de navigation

```typescript
test('doit naviguer entre les sections', async ({ page }) => {
  await page.goto('/');
  
  // Cliquer sur un lien
  await page.click('a[href="#about"]');
  
  // Vérifier que la section est visible
  const aboutSection = page.locator('#about');
  await expect(aboutSection).toBeInViewport();
});
```

### Test responsive

```typescript
test('doit afficher le menu mobile', async ({ page }) => {
  // Définir une taille mobile
  await page.setViewportSize({ width: 375, height: 667 });
  
  await page.goto('/');
  
  // Vérifier que le burger menu est visible
  const burgerMenu = page.locator('.burger-menu');
  await expect(burgerMenu).toBeVisible();
});
```

### Test d'accessibilité

```typescript
test('doit avoir des labels accessibles', async ({ page }) => {
  await page.goto('/');
  
  const inputs = page.locator('input');
  const count = await inputs.count();
  
  for (let i = 0; i < count; i++) {
    const input = inputs.nth(i);
    const id = await input.getAttribute('id');
    const label = page.locator(`label[for="${id}"]`);
    await expect(label).toBeVisible();
  }
});
```

## 🚀 Bonnes pratiques

1. **Utilisez des sélecteurs stables** : Préférez les IDs, data-testid, ou rôles plutôt que les classes CSS qui peuvent changer
2. **Évitez les timeouts fixes** : Utilisez les assertions Playwright qui attendent automatiquement
3. **Isolez les tests** : Chaque test doit être indépendant des autres
4. **Nommage clair** : Utilisez des noms de tests descriptifs en français
5. **Groupez logiquement** : Utilisez `test.describe()` pour organiser les tests

## 📚 Ressources

- [Documentation Playwright](https://playwright.dev/docs/intro)
- [API Reference](https://playwright.dev/docs/api/class-playwright)
- [Best Practices](https://playwright.dev/docs/best-practices)
