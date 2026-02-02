# Project Stack - PizzeriaSite

Derniere mise a jour: 2026-02-02

But: memo court et fiable de la stack detectee dans le repo. A relire a chaque prise en main.

## Runtime / backend
- Framework: Symfony 7.4 (voir `composer.json`).
- PHP: >= 8.2 (voir `composer.json`).
- Templating: Twig (`twig/twig`, `templates/`).
- ORM: Doctrine ORM + Migrations (`doctrine/orm`, `doctrine-migrations-bundle`, `migrations/`).
- Logs: Monolog (`symfony/monolog-bundle`).
- Security: `symfony/security-bundle`.

## Frontend / assets
- Asset Mapper + Importmap (pas de Webpack) via `symfony/asset-mapper` et `importmap.php`.
- JS: Stimulus + Turbo (`symfony/stimulus-bundle`, `symfony/ux-turbo`).
- Tailwind CSS via Symfonycasts Tailwind Bundle (binary v4.1.11) : `config/packages/symfonycasts_tailwind.yaml`.
- Entree JS principale: `assets/app.js`.

## Base de donnees
- Doctrine DBAL configure via `DATABASE_URL` (voir `.env`).
- En prod, MariaDB sur VPS avec base `Otrari` et user `Otrari`.
- Config prod effective dans `/var/www/pizzeria/.env.local`.
- Config principale: `config/packages/doctrine.yaml`.

### Entites
- **Pizza** (`src/Entity/Pizza.php`): Entite principale
  - Champs: id, name, image, price, ingredients (JSON), base (tomate/creme), type, popular, active, createdAt
  - Repository: `src/Repository/PizzaRepository.php`
  - Migration: `migrations/Version20260202000000.php`
  - Fixtures: `src/DataFixtures/PizzaFixtures.php`

## Pages et routes
- `/` (app_home): Page d'accueil avec 4 pizzas populaires
- `/pizzas` (app_pizzas): Page vitrine avec toutes les pizzas, filtres et tri par base

## Tests
- PHPUnit (`phpunit/phpunit`) et config `phpunit.dist.xml`.

## Environnements
- Fichiers: `.env`, `.env.dev`, `.env.test`. En prod, preferer des variables d'environnement reelles.

## Notes de deploiement
- Ce repo ne contient pas de config Nginx. Le vhost est gere sur le VPS.
- Prod sert `/var/www/pizzeria/public` (Nginx root).
- Le code de travail est dans `/home/mehdi/PizzeriaSite`, on deploye par rsync.

## Procedure de mise en prod (VPS)
1) Sync code:
   `sudo rsync -a --delete --exclude '.env.local' --exclude 'var/' /home/mehdi/PizzeriaSite/ /var/www/pizzeria/`
2) Migrations:
   `sudo -u www-data php bin/console doctrine:migrations:migrate --no-interaction --env=prod`
3) Fixtures (si besoin):
   `sudo -u www-data php bin/console doctrine:fixtures:load --no-interaction --env=prod`
4) Assets:
   `sudo chown -R www-data:www-data /var/www/pizzeria/public`
   `sudo -u www-data php bin/console asset-map:compile --env=prod`
   `sudo -u www-data php bin/console tailwind:build --env=prod`
5) Cache:
   `sudo -u www-data php bin/console cache:clear --env=prod --no-debug`
6) Services (si besoin):
   `sudo systemctl restart php8.3-fpm`
   `sudo systemctl restart nginx`
