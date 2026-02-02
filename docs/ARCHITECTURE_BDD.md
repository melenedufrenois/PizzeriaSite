# 📚 Fiche d'Instructions - Architecture de la Base de Données

## 🍕 Projet Pizzeria - Documentation Technique

---

## 📋 Table des Matières

1. [Vue d'ensemble](#vue-densemble)
2. [Schéma de la base de données](#schéma-de-la-base-de-données)
3. [Entités et Relations](#entités-et-relations)
4. [Configuration Docker](#configuration-docker)
5. [Installation et Démarrage](#installation-et-démarrage)
6. [Fixtures (Données de test)](#fixtures-données-de-test)
7. [Requêtes courantes](#requêtes-courantes)

---

## 🎯 Vue d'ensemble

Le projet Pizzeria utilise **Symfony 7.4** avec **Doctrine ORM** pour la gestion de la base de données. L'architecture est conçue pour gérer :

- 📦 Un catalogue de pizzas avec leurs ingrédients
- 🛒 Un système de panier d'achat
- 📝 La gestion des commandes
- 👤 L'authentification des clients

### Stack Technique

| Composant | Version | Description |
|-----------|---------|-------------|
| PHP | 8.5+ | Langage backend |
| Symfony | 7.4.x | Framework PHP |
| Doctrine ORM | 3.x | Mapping objet-relationnel |
| MySQL | 8.0 | Base de données |
| Docker | Latest | Conteneurisation |

---

## 🗺️ Schéma de la Base de Données

```
┌─────────────────┐       ┌──────────────────────┐       ┌─────────────────┐
│   ingredients   │       │  product_ingredients │       │    products     │
├─────────────────┤       ├──────────────────────┤       ├─────────────────┤
│ id (PK)         │◄──────┤ ingredient_id (FK)   │       │ id (PK)         │
│ name            │       │ product_id (FK)      │──────►│ name            │
│ price           │       └──────────────────────┘       │ description     │
│ is_available    │              ManyToMany              │ price           │
│ image           │                                      │ image           │
│ created_at      │                                      │ is_available    │
│ updated_at      │                                      │ is_popular      │
└─────────────────┘                                      │ category        │
                                                         │ created_at      │
                                                         │ updated_at      │
                                                         └────────┬────────┘
                                                                  │
                    ┌─────────────────────────────────────────────┤
                    │                                             │
                    ▼                                             ▼
          ┌─────────────────┐                           ┌─────────────────┐
          │   cart_items    │                           │   order_items   │
          ├─────────────────┤                           ├─────────────────┤
          │ id (PK)         │                           │ id (PK)         │
          │ cart_id (FK)    │                           │ order_id (FK)   │
          │ product_id (FK) │                           │ product_id (FK) │
          │ quantity        │                           │ product_name    │
          │ unit_price      │                           │ quantity        │
          │ created_at      │                           │ unit_price      │
          │ updated_at      │                           │ created_at      │
          └────────┬────────┘                           └────────┬────────┘
                   │                                             │
                   ▼                                             ▼
          ┌─────────────────┐                           ┌─────────────────┐
          │     carts       │                           │     orders      │
          ├─────────────────┤                           ├─────────────────┤
          │ id (PK)         │                           │ id (PK)         │
          │ customer_id (FK)│───────┐                   │ reference       │
          │ session_id      │       │                   │ customer_id (FK)│
          │ created_at      │       │              ┌────│ status          │
          │ updated_at      │       │              │    │ payment_status  │
          └─────────────────┘       │              │    │ total_amount    │
                                    │              │    │ delivery_*      │
                                    ▼              │    │ customer_*      │
                           ┌─────────────────┐     │    │ created_at      │
                           │   customers     │◄────┘    │ updated_at      │
                           ├─────────────────┤          └─────────────────┘
                           │ id (PK)         │
                           │ email (UNIQUE)  │
                           │ password        │
                           │ first_name      │
                           │ last_name       │
                           │ phone           │
                           │ address         │
                           │ roles           │
                           │ created_at      │
                           │ updated_at      │
                           └─────────────────┘
```

---

## 🔗 Entités et Relations

### 1. 🍕 Product (Pizza)

**Fichier:** `src/Entity/Product.php`

| Champ | Type | Description |
|-------|------|-------------|
| `id` | int | Identifiant unique |
| `name` | string(150) | Nom de la pizza |
| `description` | text | Description détaillée |
| `price` | decimal(8,2) | Prix en euros |
| `image` | string(255) | URL de l'image |
| `isAvailable` | boolean | Disponibilité |
| `isPopular` | boolean | Pizza populaire |
| `category` | string(50) | Catégorie (classique, végétarienne, etc.) |

**Relations:**
- `ManyToMany` avec **Ingredient** (une pizza a plusieurs ingrédients)
- `OneToMany` avec **CartItem** (une pizza peut être dans plusieurs paniers)
- `OneToMany` avec **OrderItem** (une pizza peut être dans plusieurs commandes)

**Méthodes utiles:**
```php
$pizza->getIngredientsAsString(); // "Tomate, Mozzarella, Basilic"
$pizza->isPopular();              // true/false
$pizza->getIngredients();         // Collection d'ingrédients
```

---

### 2. 🥬 Ingredient

**Fichier:** `src/Entity/Ingredient.php`

| Champ | Type | Description |
|-------|------|-------------|
| `id` | int | Identifiant unique |
| `name` | string(100) | Nom de l'ingrédient |
| `price` | decimal(6,2) | Prix supplémentaire |
| `isAvailable` | boolean | Disponibilité |
| `image` | string(255) | URL de l'image |

**Relations:**
- `ManyToMany` avec **Product** (inversedBy, côté propriétaire = Product)

---

### 3. 👤 Customer

**Fichier:** `src/Entity/Customer.php`

Implémente `UserInterface` et `PasswordAuthenticatedUserInterface` de Symfony Security.

| Champ | Type | Description |
|-------|------|-------------|
| `id` | int | Identifiant unique |
| `email` | string(180) | Email unique (identifiant) |
| `password` | string | Mot de passe hashé |
| `firstName` | string(100) | Prénom |
| `lastName` | string(100) | Nom de famille |
| `phone` | string(20) | Téléphone |
| `address` | text | Adresse de livraison |
| `roles` | json | Rôles (ROLE_USER, ROLE_ADMIN) |

**Relations:**
- `OneToMany` avec **Order** (un client peut avoir plusieurs commandes)
- `OneToOne` avec **Cart** (un client a un panier)

---

### 4. 🛒 Cart (Panier)

**Fichier:** `src/Entity/Cart.php`

| Champ | Type | Description |
|-------|------|-------------|
| `id` | int | Identifiant unique |
| `sessionId` | string(255) | ID de session (visiteurs anonymes) |
| `customer_id` | int | FK vers Customer (nullable) |

**Relations:**
- `OneToOne` avec **Customer** (panier du client connecté)
- `OneToMany` avec **CartItem** (articles du panier)

**Méthodes utiles:**
```php
$cart->getTotal();      // Total du panier en euros
$cart->getTotalItems(); // Nombre d'articles
```

---

### 5. 📦 CartItem (Article du panier)

**Fichier:** `src/Entity/CartItem.php`

| Champ | Type | Description |
|-------|------|-------------|
| `id` | int | Identifiant unique |
| `cart_id` | int | FK vers Cart |
| `product_id` | int | FK vers Product |
| `quantity` | int | Quantité |
| `unitPrice` | decimal(8,2) | Prix unitaire au moment de l'ajout |

**Méthodes utiles:**
```php
$item->getSubtotal(); // quantity * unitPrice
```

---

### 6. 📝 Order (Commande)

**Fichier:** `src/Entity/Order.php`

| Champ | Type | Description |
|-------|------|-------------|
| `id` | int | Identifiant unique |
| `reference` | string(50) | Référence unique (ex: ORD-2024-001) |
| `status` | string(30) | Statut de la commande |
| `paymentStatus` | string(30) | Statut du paiement |
| `totalAmount` | decimal(10,2) | Montant total |
| `deliveryAddress` | text | Adresse de livraison |
| `deliveryCity` | string(100) | Ville |
| `deliveryPostalCode` | string(10) | Code postal |
| `customerName` | string(200) | Nom du client (copie) |
| `customerEmail` | string(180) | Email (copie) |
| `customerPhone` | string(20) | Téléphone (copie) |
| `notes` | text | Notes de commande |

**Constantes de statut:**
```php
Order::STATUS_PENDING     = 'pending';      // En attente
Order::STATUS_CONFIRMED   = 'confirmed';    // Confirmée
Order::STATUS_PREPARING   = 'preparing';    // En préparation
Order::STATUS_READY       = 'ready';        // Prête
Order::STATUS_DELIVERING  = 'delivering';   // En livraison
Order::STATUS_DELIVERED   = 'delivered';    // Livrée
Order::STATUS_CANCELLED   = 'cancelled';    // Annulée

Order::PAYMENT_PENDING    = 'pending';      // En attente
Order::PAYMENT_PAID       = 'paid';         // Payée
Order::PAYMENT_FAILED     = 'failed';       // Échouée
Order::PAYMENT_REFUNDED   = 'refunded';     // Remboursée
```

---

### 7. 📋 OrderItem (Article de commande)

**Fichier:** `src/Entity/OrderItem.php`

| Champ | Type | Description |
|-------|------|-------------|
| `id` | int | Identifiant unique |
| `order_id` | int | FK vers Order |
| `product_id` | int | FK vers Product (nullable) |
| `productName` | string(150) | Nom du produit (copie pour historique) |
| `quantity` | int | Quantité |
| `unitPrice` | decimal(8,2) | Prix unitaire |

> **Note:** `productName` est copié pour conserver l'historique même si le produit est supprimé.

---

## 🐳 Configuration Docker

### Fichier `compose.yaml`

```yaml
services:
  database:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_DATABASE: pizzeria
      MYSQL_USER: pizzeria_user
      MYSQL_PASSWORD: pizzeria_password
    ports:
      - "3306:3306"
    volumes:
      - database_data:/var/lib/mysql

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    environment:
      PMA_HOST: database
      PMA_USER: pizzeria_user
      PMA_PASSWORD: pizzeria_password
    ports:
      - "8080:80"
    depends_on:
      - database

volumes:
  database_data:
```

### Configuration `.env`

```bash
DATABASE_URL="mysql://pizzeria_user:pizzeria_password@127.0.0.1:3306/pizzeria?serverVersion=8.0.32&charset=utf8mb4"
```

---

## 🚀 Installation et Démarrage

### Étape 1: Démarrer les conteneurs Docker

```bash
docker compose up -d
```

### Étape 2: Vérifier que MySQL est prêt

```bash
docker compose logs -f database
# Attendre le message "ready for connections"
```

### Étape 3: Créer les migrations

```bash
php bin/console make:migration
```

### Étape 4: Exécuter les migrations

```bash
php bin/console doctrine:migrations:migrate
```

### Étape 5: Charger les fixtures (données de test)

```bash
php bin/console doctrine:fixtures:load
```

### Étape 6: Démarrer le serveur Symfony

```bash
php bin/console tailwind:build
symfony serve -d
# ou
php -S localhost:8000 -t public
```

### Accès

| Service | URL | Identifiants |
|---------|-----|--------------|
| Application | http://localhost:8000 | - |
| phpMyAdmin | http://localhost:8080 | pizzeria_user / pizzeria_password |

---

## 🌱 Fixtures (Données de test)

Le fichier `src/DataFixtures/AppFixtures.php` crée :

### 22 Ingrédients
- Base: Sauce tomate, Crème fraîche
- Fromages: Mozzarella, Parmesan, Gorgonzola, Chèvre, Emmental
- Viandes: Jambon, Chorizo, Pepperoni, Poulet, Lardons, Boeuf haché
- Légumes: Champignons, Olives, Poivrons, Oignons, Artichauts, Roquette, Tomates cerises
- Autres: Anchois, Thon, Ananas

### 12 Pizzas

| Pizza | Prix | Catégorie | Populaire |
|-------|------|-----------|-----------|
| Margherita | 10,90€ | Classique | ✅ |
| Regina | 13,50€ | Classique | ✅ |
| 4 Fromages | 14,90€ | Classique | ✅ |
| Pepperoni | 13,50€ | Classique | ✅ |
| Calzone | 14,50€ | Classique | ❌ |
| Végétarienne | 12,90€ | Végétarienne | ❌ |
| Hawaïenne | 13,50€ | Classique | ❌ |
| Mexicaine | 14,90€ | Épicée | ❌ |
| Forestière | 13,90€ | Végétarienne | ❌ |
| Napolitaine | 12,50€ | Classique | ❌ |
| Poulet BBQ | 14,50€ | Classique | ❌ |
| Thon | 13,50€ | Classique | ❌ |

---

## 🔍 Requêtes Courantes

### Dans les Repositories

```php
// ProductRepository - Toutes les pizzas disponibles avec ingrédients
$products = $productRepository->findAllAvailableWithIngredients();

// ProductRepository - Pizzas populaires
$popular = $productRepository->findPopular();

// ProductRepository - Par catégorie
$vegetariennes = $productRepository->findByCategory('végétarienne');

// OrderRepository - Commandes d'un client
$orders = $orderRepository->findByCustomer($customer);

// OrderRepository - Par statut
$pending = $orderRepository->findByStatus(Order::STATUS_PENDING);

// CartRepository - Panier par session
$cart = $cartRepository->findBySessionId($sessionId);
```

### Exemples DQL

```php
// Pizzas avec au moins 5 ingrédients
$qb = $em->createQueryBuilder();
$qb->select('p')
   ->from(Product::class, 'p')
   ->leftJoin('p.ingredients', 'i')
   ->groupBy('p.id')
   ->having('COUNT(i.id) >= 5');

// Total des ventes par pizza
$qb = $em->createQueryBuilder();
$qb->select('p.name, SUM(oi.quantity) as totalVendu')
   ->from(OrderItem::class, 'oi')
   ->join('oi.product', 'p')
   ->groupBy('p.id')
   ->orderBy('totalVendu', 'DESC');
```

---

## 📁 Structure des Fichiers

```
src/
├── Controller/
│   └── PageController.php          # Contrôleur de la page d'accueil
├── DataFixtures/
│   └── AppFixtures.php             # Données de test
├── Entity/
│   ├── Cart.php                    # Entité Panier
│   ├── CartItem.php                # Article du panier
│   ├── Customer.php                # Client/Utilisateur
│   ├── Ingredient.php              # Ingrédient
│   ├── Order.php                   # Commande
│   ├── OrderItem.php               # Article de commande
│   └── Product.php                 # Pizza/Produit
└── Repository/
    ├── CartItemRepository.php
    ├── CartRepository.php
    ├── CustomerRepository.php
    ├── IngredientRepository.php
    ├── OrderItemRepository.php
    ├── OrderRepository.php
    └── ProductRepository.php
```

---

## ✅ Checklist de Déploiement

- [ ] Configurer les variables d'environnement de production
- [ ] Changer `APP_ENV=prod` et `APP_DEBUG=0`
- [ ] Configurer une vraie base de données MySQL
- [ ] Exécuter les migrations
- [ ] Configurer HTTPS
- [ ] Configurer le système de mailing pour les notifications
- [ ] Mettre en place les sauvegardes de la base de données

---

## 📞 Support

Pour toute question concernant l'architecture, consultez la documentation Symfony :
- [Doctrine ORM](https://symfony.com/doc/current/doctrine.html)
- [Symfony Security](https://symfony.com/doc/current/security.html)
- [Doctrine Fixtures](https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html)

---

*Documentation générée pour le projet Pizzeria - Symfony 7.4*
