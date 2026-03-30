# Plan d'Implémentation - FEATURE-001: Backoffice Administrateur

## Phases d'implémentation

### Phase 1: Sécurité et Authentification (JWT)
**Objectif**: Mettre en place le système d'authentification JWT pour l'admin

| # | Tâche | Fichier(s) | Détails |
|---|-------|------------|---------|
| 1.1 | Installer lexik/jwt-authentication-bundle | `composer.json` | `composer require lexik/jwt-authentication-bundle` |
| 1.2 | Générer les clés JWT | `/config/jwt/` | `openssl genrsa -out config/jwt/private.pem 4096` + public key |
| 1.3 | Créer entité User | `src/Entity/User.php` | Propriétés: id, email, password, roles, createdAt |
| 1.4 | Créer UserRepository | `src/Repository/UserRepository.php` | Étend ServiceEntityRepository |
| 1.5 | Configurer security.yaml | `config/packages/security.yaml` | Ajouter provider, firewall JWT, access_control |
| 1.6 | Créer LoginController | `src/Controller/Admin/AuthController.php` | Route `/api/login` - retourne JWT token |
| 1.7 | Créer fixture admin | `src/DataFixtures/AdminUserFixture.php` | Crée le premier utilisateur admin |
| 1.8 | Tester authentication JWT | - | Vérifier login + token |

### Phase 2: Modifications Base de données
**Objectif**: Ajouter le champ displayOrder et finaliser les entités

| # | Tâche | Fichier(s) | Détails |
|---|-------|------------|---------|
| 2.1 | Ajouter displayOrder dans Product | `src/Entity/Product.php` | Champ integer, nullable, default 0 |
| 2.2 | Créer migration | `migrations/VersionYYYYMMDDHHMMSS.php` | Ajoute colonne display_order |
| 2.3 | Exécuter migration | - | `php bin/console doctrine:migrations:migrate` |

### Phase 3: Form Types
**Objectif**: Créer les formulaires pour les produits

| # | Tâche | Fichier(s) | Détails |
|---|-------|------------|---------|
| 3.1 | Créer ProductForm (base) | `src/Form/Admin/ProductType.php` | Champs: name, description, image, price, type, active, popular, displayOrder |
| 3.2 | Créer PizzaForm | `src/Form/Admin/PizzaType.php` | Hérite de ProductType + base, ingredients |
| 3.3 | Créer PastaForm | `src/Form/Admin/PastaType.php` | Hérite de ProductType + ingredients, pastaType |
| 3.4 | Créer DessertForm | `src/Form/Admin/DessertType.php` | Hérite de ProductType + containsAllergens |
| 3.5 | Créer DrinkForm | `src/Form/Admin/DrinkType.php` | Hérite de ProductType + volume, isAlcoholic |

### Phase 4: Controller Admin
**Objectif**: Créer le controller avec toutes les routes CRUD

| # | Tâche | Fichier(s) | Détails |
|---|-------|------------|---------|
| 4.1 | Créer AdminController | `src/Controller/Admin/ProductController.php` | Routes principales |
| 4.2 | Implémenter liste produits | - | GET `/admin/products` avec filtres catégorie |
| 4.3 | Implémenter création | - | GET/POST `/admin/product/new` |
| 4.4 | Implémenter modification | - | GET/POST `/admin/product/{id}/edit` |
| 4.5 | Implémenter suppression | - | DELETE `/admin/product/{id}` (hard delete) |
| 4.6 | Implémenter changement dispo | - | POST `/admin/product/{id}/toggle` (toggle active) |

### Phase 5: Templates Backoffice
**Objectif**: Créer l'interface utilisateur admin

| # | Tâche | Fichier(s) | Détails |
|---|-------|------------|---------|
| 5.1 | Créer layout admin | `templates/admin/base.html.twig` | Header, sidebar, content, couleurs Pizzeria |
| 5.2 | Créer template liste | `templates/admin/products/index.html.twig` | Table, filtres catégorie, actions |
| 5.3 | Créer template formulaire | `templates/admin/products/_form.html.twig` | Formulaire réutilisable |
| 5.4 | Créer template création | `templates/admin/products/new.html.twig` | Page nouveau produit |
| 5.5 | Créer template édition | `templates/admin/products/edit.html.twig` | Page edition produit |

### Phase 6: Intégration et Tests
**Objectif**: Vérifier que tout fonctionne

| # | Tâche | Détails |
|---|-------|---------|
| 6.1 | Tester connexion admin | Vérifier JWT token |
| 6.2 | Tester CRUD complet | Create, Read, Update, Delete |
| 6.3 | Tester filtres catégorie | Vérifier filtrage |
| 6.4 | Tester affichage site public | Modifs visibles immédiatement |
| 6.5 | Tester validation | Champs obligatoires |

---

## Détail des Routes

```
/admin                          # Redirect vers /admin/products
/admin/products                 # Liste produits (GET)
/admin/products/new             # Créer produit (GET/POST)
/admin/product/{id}             # Voir produit (GET)
/admin/product/{id}/edit        # Éditer produit (GET/POST)
/admin/product/{id}             # Supprimer produit (DELETE)
/admin/product/{id}/toggle      # Toggle active (POST)

/api/login                      # Connexion JWT (POST) - publique
/api/login_check                # JWT check - publique
```

---

## Dépendances entre tâches

```
Phase 1 (Sécurité)
├── 1.1 → 1.2 → 1.3 → 1.4 → 1.5 → 1.6 → 1.7 → 1.8
└── Nécessite: security.yaml configuré avant Phase 4

Phase 2 (DB)
├── 2.1 → 2.2 → 2.3
└── Nécessite: Phase 1.3 (User entity) pas nécessaire

Phase 3 (Forms)
├── 3.1 → 3.2, 3.3, 3.4, 3.5
└── Nécessite: Phase 2.3 (migration faite)

Phase 4 (Controller)
├── Tout dépend de Phase 1.5 (security.yaml) et Phase 3
└── Nécessite: Routes dispo, forms créés

Phase 5 (Templates)
├── Tout dépend de Phase 4
└── Nécessite: Controller et forms prêts

Phase 6 (Tests)
└── Dépend de toutes les phases
```

---

## Ressources estimées

| Phase | Temps estimé |
|-------|--------------|
| Phase 1: Sécurité | 2-3 heures |
| Phase 2: DB | 30 minutes |
| Phase 3: Forms | 1-2 heures |
| Phase 4: Controller | 2-3 heures |
| Phase 5: Templates | 2-3 heures |
| Phase 6: Tests | 1-2 heures |
| **Total** | **9-14 heures** |

---

## Notes d'implémentation

### Couleurs à utiliser (CSS)
```css
--color-pieza-green: #2D3B2F;
--color-pieza-cream: #F5F1E8;
--color-pieza-orange: #FF6B4A;
```

### Validation formulaire
- Nom: NotBlank, Length(max=100)
- Prix: Positive (gt=0)
- Image: Url (ou optional)
- Catégorie: Choice valide

### Suppression
- Hard delete via `$entityManager->remove($product)`
- Pas de soft delete (conformément au ticket)

### API JWT
- Token expiration: 24h (configurable)
- Format réponse login:
```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "user": {
    "id": 1,
    "email": "admin@pizzeria.fr",
    "roles": ["ROLE_ADMIN"]
  }
}
```
