---
type: feature
priority: high
created: 2026-03-30T10:00:00Z
status: created
tags: [admin, backoffice, CRUD, produit, gestion]
keywords: [admin, backoffice, CRUD, Product, Pizza, sécurité, JWT, authentication]
patterns: [Symfony CRUD, authentication, form validation, image upload]
---

# FEATURE-001: Backoffice administrateur pour la gestion des produits

## Description
Créer une interface d'administration permettant à un administrateur de gérer les produits (pizzas, pâtes, desserts, boissons) depuis le backoffice. L'admin pourra créer, modifier et supprimer des plats via une interface dédiée accessible à l'URL `/admin`.

## Contexte
Le site public de la pizzeria affiche les produits (pizzas, pâtes, desserts, boissons) mais il n'existe pas d'interface pour les gérer facilement. Actuellement, les modifications nécessitent un accès direct à la base de données ou des déploiements. Ce ticket vise à créer une interface d'administration complète.

## Spécifications fonctionnelles

### Utilisateur Administrateur
- **Nombre d'administrateurs** : 1 (avec possibilité d'extension ultérieure)
- **Système d'authentification** : JWT avec login/mot de passe
- **URL du backoffice** : `/admin`
- **Gestion des utilisateurs** : Créer le premier utilisateur admin via commande Symfony ou fixture

### Gestion des Produits (CRUD)
- **Créer** : Ajouter un nouveau produit avec tous les champs
- **Lire** : Voir la liste des produits et les détails d'un produit
- **Modifier** : Éditer un produit existant
- **Supprimer** : Suppression définitive (hard delete)

### Liste des produits
- **Vue unifiée** avec filtres par catégorie (Pizzas, Pâtes, Desserts, Boissons)
- **Champs à afficher** : Image, Titre, Prix, Catégorie, Disponibilité

### Formulaire de produit
**Champs du formulaire** :
| Champ | Type | Requis | Description |
|-------|------|--------|-------------|
| titre (name) | string | Oui | Nom du produit |
| description | text | Non | Description/Ingrédients |
| image | string (URL) | Oui | URL de l'image |
| prix (price) | decimal | Oui | Prix du produit |
| catégorie (type) | choice | Oui | Pizza/Pâte/Dessert/Boisson |
| disponibilité (active) | boolean | Oui | Produit visible ou non |
| ordre d'affichage (displayOrder) | integer | Non | Ordde de tri |
| populaire (popular) | boolean | Non | Produit populaire |

**Pour les pizzas uniquement** :
| Champ | Type | Requis | Description |
|-------|------|--------|-------------|
| base | choice | Non | Base tomate ou crème |
| ingredients | JSON array | Non | Liste des ingrédients |

### Validation
- **Prix** : Nombre positif
- **Nom** : Non vide, max 100 caractères
- **Image** : URL valide (format à valider)
- **Catégorie** : Valeur autorisée

### Design
- **Couleurs à utiliser** :
  - Vert : `#2D3B2F`
  - Crème : `#F5F1E8`
  - Orange : `#FF6B4A`
- **Style** : À définir (reprendre le design existant du site public)

## État actuel
- Entités existantes : `Product` (abstraite), `Pizza`, `Pasta`, `Dessert`, `Drink`
- Champ `displayOrder` : À créer (n'existe pas encore)
- Système d'authentification admin : À créer (JWT)
- Routes admin : À créer

## État désiré
- Page `/admin` accessible uniquement aux utilisateurs avec le rôle ADMIN
- Liste des produits avec filtres par catégorie
- Formulaire de création/modification de produit
- Suppression définitive d'un produit
- Modifications visibles immédiatement sur le site public

## Contexte de recherche

### Mots-clés à rechercher
- `Product` - Entité de base des produits
- `Pizza`, `Pasta`, `Dessert`, `Drink` - Entités enfants
- `JWT`, `authentication`, `security` - Système d'authentification
- `Symfony MakerBundle`, `EasyAdmin` - Génération du CRUD
- `VichUploader` ou gestion d'images - Upload d'images (si besoin)
- `form validation` - Validation des formulaires

### Patterns à investiguer
- **Architecture Symfony** : Comment le projet structure les controllers et services
- **Form Symfony** : Comment les formulaires sont créés et validés
- **Doctrine** : Comment les entités et repositories sont utilisés
- **Sécurité** : Comment les routes sont protègées par rôles

### Décisions déjà prises
- **URL** : `/admin`
- **Authentification** : JWT avec login/mot de passe
- **Suppression** : Définitive (hard delete)
- **Visibilité** : Immédiate (pas de système de publication)
- **Couleurs** : `#2D3B2F` (vert), `#F5F1E8` (crème), `#FF6B4A` (orange)

## Critères de succès

### Vérification automatisée
- [ ] L'admin peut se connecter via JWT
- [ ] L'admin peut accéder à `/admin` (403 si non connecté)
- [ ] La liste des produits s'affiche avec les filtres
- [ ] La création d'un produit fonctionne
- [ ] La modification d'un produit fonctionne
- [ ] La suppression d'un produit fonctionne (hard delete)
- [ ] Les modifications sont visibles sur le site public
- [ ] Les images s'affichent correctement
- [ ] La validation des champs obligatoires fonctionne

### Vérification manuelle
- [ ] Connexion admin avec identifiants valides
- [ ] Accès refusé si non connecté
- [ ] Formulaire avec tous les champs visibles
- [ ] Filtres de catégorie fonctionnels
- [ ] Images affichées dans le backoffice et le site public
- [ ] Messages d'erreur pour champs invalides

## Informations liées
- Entités existantes dans `src/Entity/`
- Repositories dans `src/Repository/`
- Fixtures existantes dans `src/DataFixtures/`
- Styles dans `assets/styles/app.css`

## Notes
- La fonctionnalité "ordre d'affichage" nécessite l'ajout d'un champ `displayOrder` dans l'entité `Product`
- Pour les pâtes, desserts et boissons, le champ `ingredients` n'existe pas - utiliser `description` ou créer un champ similaire
- Prévoir une commande Symfony pour créer le premier utilisateur admin

---

## Phase de Recherche - Résultats

### État actuel du projet

| Aspect | Status | Action requise |
|--------|--------|----------------|
| **Sécurité** | Empty (provider in-memory vide) | Créer entité User, configurer form login ou JWT |
| **Controllers** | Prêt | Créer AdminController avec routes CRUD |
| **Forms** | Pattern existant | Créer ProductType, PizzaType, etc. |
| **Entities** | Complètes | Utiliser la hiérarchie Product existante |
| **Repositories** | Complets | Utiliser les repositories existants |
| **Routes** | Attributs PHP 8 | Ajouter routes admin via attributs |
| **Templates** | Pattern Tailwind existant | Créer layout admin et templates |

### Configuration sécurité actuelle
- Fichier : `config/packages/security.yaml`
- Provider : `users_in_memory` (vide, aucun utilisateur)
- Firewall : `main` (lazy)
- Access control : Commenté (pas de restriction)

### Pattern Controller utilisé
```php
#[Route('/admin', name: 'app_admin_')]
class AdminController extends AbstractController
{
    // Routes via attributs PHP 8 #[Route]
}
```

### Pattern Form utilisé
- **Model** : `src/Form/Model/` - DTOs avec contraintes de validation
- **Type** : `src/Form/Type/` - Classes étendant `AbstractType`
- Exemple existant : `ContactRequestType` + `ContactRequestData`

### Entités existantes
- **Product** (abstraite) : name, description, image, price, type, popular, active, allergens, createdAt
- **Pizza** : base (tomate/creme), ingredients (JSON)
- **Pasta** : ingredients, pastaType
- **Dessert** : ingredients, containsAllergens
- **Drink** : volume, isAlcoholic

### Bundle JWT nécessaire
- **lexik/jwt-authentication-bundle** : Non installé
- À installer : `composer require lexik/jwt-authentication-bundle`
- Configuration : Générer clés JWT, configure security.yaml

### Recommandations d'implémentation

1. **Créer entité User** avec rôles (ROLE_ADMIN)
2. **Installer lexik/jwt-authentication-bundle** pour JWT
3. **Configurer security.yaml** avec JWT ou form login
4. **Créer AdminController** avec actions CRUD
5. **Créer Form Types** pour chaque type de produit
6. **Créer templates admin** dans `templates/admin/`
7. **Ajouter access_control** pour routes `/admin`
