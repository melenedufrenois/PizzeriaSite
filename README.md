# Pizzeria O'Trari - Site Vitrine

Bienvenue sur le site officiel de la **Pizzeria O'Trari**. Un site vitrine moderne et élégant présentant nos délicieuses pizzas et services.

## 📋 À propos

La Pizzeria O'Trari est un établissement proposant une sélection de pizzas artisanales réalisées avec des ingrédients de qualité. Ce site vitrine permet aux clients de découvrir notre offre, consulter nos menus et nous contacter facilement.

## 🛠 Technologies utilisées

- **Framework** : Symfony 6.x
- **Langage serveur** : PHP 8.x
- **Base de données** : Doctrine ORM
- **Frontend** : JavaScript (Stimulus), CSS
- **Templating** : Twig
- **Conteneurisation** : Docker Compose

## 🚀 Installation et démarrage

### Prérequis

- PHP 8.1+
- Composer
- Node.js 16+ (pour les assets)
- Docker & Docker Compose (optionnel)

### Étapes d'installation

1. **Cloner le projet**
   ```bash
   git clone <repository-url>
   cd pizzeria
   ```

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Configurer l'environnement**
   ```bash
   cp .env.example .env
   # Éditer le fichier .env avec vos configurations
   ```

4. **Installer les dépendances JavaScript**
   ```bash
   npm install
   ```

5. **Construire les assets**
   ```bash
   npm run build
   ```

6. **Démarrer avec Docker Compose**
   ```bash
   docker-compose up -d
   ```

7. **Accéder au site**
   - L'application est disponible sur `http://localhost`

## 📁 Structure du projet

```
pizzeria/
├── assets/              # Fichiers CSS et JavaScript
├── bin/                 # Scripts exécutables
├── config/              # Configuration de l'application
├── migrations/          # Migrations de base de données
├── public/              # Fichiers publics et point d'entrée
├── src/                 # Code source (Controllers, Entities, etc.)
├── templates/           # Fichiers Twig HTML
├── vendor/              # Dépendances Composer
└── var/                 # Cache et logs
```

## ✨ Fonctionnalités principales (pour le moment)

- 🍕 Affichage du catalogue de pizzas

## 🧪 Tests automatisés

Le projet utilise [Playwright](https://playwright.dev/) pour les tests end-to-end (E2E).

### Structure des tests

Les tests sont organisés dans le dossier `tests/e2e/` :
- `homepage.spec.ts` : Tests de la page d'accueil
- `menu.spec.ts` : Tests du menu des pizzas
- `navigation.spec.ts` : Tests de navigation
- `accessibility.spec.ts` : Tests d'accessibilité

### Exécuter les tests

1. **Installer les dépendances de test**
   ```bash
   npm install
   npx playwright install chromium
   ```

2. **Démarrer le serveur de développement**
   ```bash
   php -S localhost:8000 -t public
   # ou avec Symfony CLI
   symfony server:start
   ```

3. **Exécuter les tests**
   ```bash
   # Tous les tests
   npm test
   
   # Tests en mode interface (UI mode)
   npm run test:ui
   
   # Tests en mode visible (headed)
   npm run test:headed
   
   # Tests en mode debug
   npm run test:debug
   
   # Voir le rapport
   npm run test:report
   ```

### Configuration

La configuration Playwright se trouve dans `playwright.config.ts`. Par défaut :
- Les tests s'exécutent sur Chromium
- L'URL de base est `http://localhost` (peut être modifiée via `BASE_URL`)
- Les captures d'écran sont prises en cas d'échec
- Les traces sont enregistrées lors du premier retry

## 📝 Variables d'environnement

Voir le fichier `.env` pour configurer :
- `DATABASE_URL` : URL de connexion à la base de données
- `MAILER_DSN` : Configuration du service de mail
- `APP_ENV` : Environnement (dev/prod)

## 👥 Contributeurs

Équipe de développement - AcLab
- Trari Mehdi
- Dufrénois Mélène
- Duvivier Sacha
- Kenouz Abdelghani
- Dadon Théo

---

**Pizzeria O'Trari** 🍕
