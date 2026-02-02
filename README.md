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

### Identifiants BDD

| Paramètre | Valeur |
|-----------|--------|
| Hôte | database |
| Base | pizzeria |
| Utilisateur | pizzeria_user |
| Mot de passe | pizzeria_password |

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
