# 🍕 Pizzeria O'Trari

Application web de pizzeria développée avec Symfony 7.4.

## 🐳 Démarrage rapide avec Docker

### Prérequis

- [Docker](https://www.docker.com/get-started) installé
- [Docker Compose](https://docs.docker.com/compose/install/) installé

### Lancement (une seule commande !)

**Windows :**
```bash
.\start.bat
```

**Linux/Mac :**
```bash
chmod +x start.sh && ./start.sh
```

**OU manuellement :**
```bash
# Construire et démarrer
docker compose up -d --build

# Attendre 30 secondes que MySQL démarre, puis :
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction
docker compose exec php php bin/console doctrine:fixtures:load --no-interaction
docker compose exec php php bin/console tailwind:build
```

### 🌐 Accès

| Service | URL | Description |
|---------|-----|-------------|
| **Application** | http://localhost:8000 | Site Pizzeria |
| **phpMyAdmin** | http://localhost:8080 | Administration BDD |
| **Page accès** | http://localhost:8000/acces | Carte interactive & itinéraire |
| **Mailpit** | http://localhost:8025 | Inbox email locale (dev) |

### Identifiants BDD

| Paramètre | Valeur |
|-----------|--------|
| Hôte | database |
| Base | pizzeria |
| Utilisateur | pizzeria_user |
| Mot de passe | `changeme` par défaut en Docker local, sinon valeur de `MYSQL_PASSWORD` |

---

## 🛠️ Commandes utiles

```bash
# Voir les logs
docker compose logs -f

# Accéder au conteneur PHP
docker compose exec php bash

# Reconstruire Tailwind
docker compose exec php php bin/console tailwind:build

# Vider le cache
docker compose exec php php bin/console cache:clear

# Recharger les fixtures
docker compose exec php php bin/console doctrine:fixtures:load --no-interaction

# Arrêter les conteneurs
docker compose down

# Arrêter et supprimer les volumes (reset complet)
docker compose down -v
```

---

## 📁 Structure du projet

```
PizzeriaSite/
├── docker/
│   └── nginx/
│       └── default.conf      # Config Nginx
├── src/
│   ├── Controller/           # Contrôleurs Symfony
│   ├── Entity/               # Entités Doctrine
│   ├── Repository/           # Repositories
│   └── DataFixtures/         # Données de test
├── templates/                # Templates Twig
├── migrations/               # Migrations Doctrine
├── docs/                     # Documentation
├── compose.yaml              # Docker Compose
├── Dockerfile                # Image PHP
├── start.bat                 # Script Windows
└── start.sh                  # Script Linux/Mac
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
- L'URL de base est `http://localhost:8000` (peut être modifiée via `BASE_URL`)
- Les captures d'écran sont prises en cas d'échec
- Les traces sont enregistrées lors du premier retry

## 📝 Variables d'environnement

Voir le fichier `.env` pour configurer :
- `DATABASE_URL` : URL de connexion à la base de données
- `MAILER_DSN` : Configuration SMTP du service de mail
- `APP_ENV` : Environnement (dev/prod)
- `GOOGLE_MAPS_API_KEY` : Clé API Google Maps Embed
- `PIZZERIA_MAP_LATITUDE` : Latitude de la pizzeria
- `PIZZERIA_MAP_LONGITUDE` : Longitude de la pizzeria
- `PIZZERIA_MAP_ADDRESS` : Libellé de l’adresse affichée sur la page d’accès
- `CONTACT_DEFAULT_RECIPIENT` : Destinataire email par défaut du formulaire de contact
- `CONTACT_SENDER_EMAIL` : Expéditeur utilisé pour les emails de contact

Par environnement :
- `.env.dev` : `MAILER_DSN=smtp://127.0.0.1:1025` (Mailpit local)
- `.env.prod` : `MAILER_DSN=smtp://...` (SMTP réel de production)

## 🚀 Déploiement automatique GitHub -> prod

Un workflow GitHub Actions déclenche automatiquement `deploy.sh` à chaque `push` sur la branche `prod`, via un runner GitHub Actions self-hosted installé sur le VPS de production.

Le workflow exécute ensuite :

```bash
cd /home/mehdi/PizzeriaSite && bash ./deploy.sh --branch prod
```

Prérequis côté serveur :

- Installer un runner GitHub Actions self-hosted sur le VPS pour ce dépôt
- Enregistrer le runner avec les labels `self-hosted`, `linux`, `x64`
- Faire tourner le runner avec un utilisateur qui peut exécuter `deploy.sh` sans interaction

Important : le compte système qui exécute le runner doit pouvoir lancer `deploy.sh` sans prompt. Si ce n'est pas `root`, il lui faut du `sudo` sans mot de passe pour les commandes utilisées par le script (`rsync`, `chown`, `chmod`, etc.).

Une fois le runner installé, les secrets SSH GitHub (`PROD_DEPLOY_HOST`, `PROD_DEPLOY_PORT`, `PROD_DEPLOY_USER`, `PROD_DEPLOY_SSH_KEY`, `PROD_DEPLOY_KNOWN_HOSTS`) ne sont plus nécessaires pour ce workflow.

## ✉️ Formulaire de contact

- Route : `GET|POST /contact`
- Types de demande : réservation, devis, question, événement
- Validation obligatoire côté front et côté back
- Envoi email via Symfony Mailer
- En `dev`, les emails sont envoyés immédiatement vers Mailpit (sans worker Messenger à lancer)
- En `prod`, la route mail reste asynchrone via Messenger (`async`)

### Destinataire configurable en base de données

Le destinataire peut être modifié sans redéploiement :

```bash
php bin/console app:contact:set-recipient contact@otrexemple.fr
```

## 👥 Contributeurs

Équipe de développement - AcLab
- Trari Mehdi
- Dufrénois Mélène
- Duvivier Sacha
- Kenouz Abdelghani
- Dadon Théo
- Fischer Martin

---

## 📊 Base de données

### Entités

| Entité | Description |
|--------|-------------|
| **Product** | Pizzas (nom, description, prix) |
| **Ingredient** | Ingrédients (ManyToMany avec Product) |
| **Customer** | Clients (authentification) |
| **Cart/CartItem** | Panier d'achat |
| **Order/OrderItem** | Commandes |

### Fixtures

- 22 ingrédients
- 12 pizzas

📖 Documentation complète : [docs/ARCHITECTURE_BDD.md](docs/ARCHITECTURE_BDD.md)

---

## 🔧 Développement local (sans Docker)

```bash
# Prérequis : PHP 8.3+, MySQL 8.0, Composer

composer install
# Configurer DATABASE_URL dans .env.local
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
php bin/console tailwind:build
php -S localhost:8000 -t public
```

---

## 🛠 Stack technique

| Composant | Version |
|-----------|---------|
| PHP | 8.3 |
| Symfony | 7.4 |
| MySQL | 8.0 |
| Nginx | Alpine |
| Docker | Latest |
| Tailwind CSS | 4.x |

---

## 👥 Équipe

Projet Master Cybersécurité - AcLab

## 📄 Licence

Projet académique.
