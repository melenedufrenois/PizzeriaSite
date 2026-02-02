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

**SCRUM MASTER REVIEW**

Voici un document **prêt à coller** dans votre repo (ex: `TEAM_AGREEMENTS.md` ou `WORKING_AGREEMENT.md`).
Il contient les **exigences**, les **accords d’équipe**, le **planning**, et la **liste des US** (faits + en cours).

---

# ACLAB — O’Trari / PizzeriaSite

## Exigences & Accords d’équipe (Working Agreement)

### 1) Contexte & objectif

Projet ACLAB : développement d’un site vitrine de pizzeria **O’Trari** en **Symfony + PHP**.
Objectif court terme : livrer une **page vitrine** propre et organisée (cartes pizzas), avec **catégorisation**, **filtres**, et **tests automatisés**.

---

## 2) Planning de réalisation (journée)

### Bloc 1 — 08h00 → 12h00 (4h) ✅ Terminé

Objectif : finaliser les 4 premiers US.

### Bloc 2 — 13h00 → 18h00 (5h) 🔄 En cours

Objectif : finaliser les 4 US restants (US5 à US8).

---

## 3) Backlog / Tickets GitHub (état actuel)

### ✅ US terminées (Closed)

* **US 1 — Initialisation projet** (fait)
* **US 2 — Créer le layout de base** (fait)
* **US 3 — Page vitrine pizzas** (fait)
* **US 4 — Mise en ligne** (fait)

### 🔄 US en cours (Open)

* **US 5 — Tests**
* **US 6 — Catégorisation base de pizza** (tomate / crème)
* **US 7 — Filtres**
* **US 8 — Tests**

> ⚠️ Note équipe : US5 et US8 sont tous les deux “Tests”.
> Décision recommandée :
>
> * **US5 = tests applicatifs Symfony/PHP** (ex: PHPUnit / tests de logique)
> * **US8 = tests E2E automatisés Playwright** (parcours utilisateur vitrine)
>   À valider avec le PO pour éviter doublon.

---

## 4) Exigences fonctionnelles (produit)

### 4.1 Page vitrine (Home)

* Afficher la liste des pizzas sous forme de **cartes**
* Chaque carte contient :

  * Nom
  * Ingrédients
  * Description
  * Prix

### 4.2 Catégorisation (US6)

* Les pizzas doivent être **organisées par base** :

  * **Base tomate**
  * **Base crème fraîche**
* Une pizza appartient à **une seule** catégorie de base

### 4.3 Filtres (US7)

* Le visiteur doit pouvoir filtrer l’affichage :

  * Toutes
  * Base tomate
  * Base crème
* Le filtre doit être visible et simple (UI claire)

### 4.4 Tests & automatisation (US5/US8)

* Mise en place de tests pour sécuriser la livraison
* Mise en place d’un **script automatisé Playwright** (E2E) pour valider des parcours clés

---

## 5) Exigences techniques (tech)

* Stack : Symfony + PHP
* Base de données : **SQLite au départ**, puis **MySQL** (selon organisation Docker / consigne)
* Code structuré + réutilisable (composants Twig, etc.)
* Couleurs/design : **Orange / Blanc / Noir** (cf. vos docs design)

---

# 6) Accords d’équipe (règles obligatoires)

## 6.1 Git / Branching (OBLIGATOIRE)

✅ **Une branche par feature / US**

* Convention recommandée :

  * `feature/us6-categorisation-base`
  * `feature/us7-filtres`
  * `test/us8-playwright`
  * `fix/...`

✅ **Interdiction de push directement sur `main`**
✅ Toute intégration passe par une **Pull Request**.

## 6.2 Merge vers `main`

✅ **On attend l’approbation du PO** avant de merge dans `main`

* PO valide : conformité fonctionnelle + rendu attendu + pas de régression

✅ Merge uniquement si :

* CI/build OK (si mis en place)
* Code review OK
* DoD respectée (voir section 7)

---

## 6.3 Communication (Discord)

✅ Toutes les conversations importantes + nouveaux tickets doivent être **relayés sur Discord**
Objectif : que chaque dev reçoive une notification et reste aligné.

Règle :

* Nouvelle issue créée → message Discord avec :

  * numéro du ticket
  * titre
  * lien
  * résumé 1 ligne
* Changement de statut (In progress / PR / Done) → message court Discord

---

## 6.4 Documentation obligatoire par feature (OBLIGATOIRE)

Chaque feature terminée doit avoir une mini doc pour les futurs développeurs.

📌 Format recommandé :

* Un fichier par feature dans `/docs/`

  * `docs/us6-categorisation.md`
  * `docs/us7-filtres.md`
  * `docs/us8-tests-playwright.md`

Contenu minimum :

* Objectif de la feature
* Comment ça marche (règles / logique)
* Où est le code (fichiers impactés)
* Comment tester (manuel + commandes)
* Cas limites / décisions prises

---

## 6.5 Script automatisé Playwright (OBLIGATOIRE)

Objectif : automatiser des tests E2E (parcours vitrine).

✅ Le repo doit contenir :

* un script Playwright + une doc de lancement

Exemples de scénarios E2E (minimum) :

* La home s’ouvre sans erreur
* Les pizzas s’affichent en cartes
* La catégorisation tomate/crème fonctionne
* Le filtre “tomate” affiche uniquement les pizzas tomate
* Le filtre “crème” affiche uniquement les pizzas crème

---

# 7) Definition of Done (DoD) — pour fermer un ticket

Un ticket est “Done” seulement si :

* [ ] Code fonctionnel + conforme au ticket
* [ ] PR ouverte + review faite
* [ ] Validation PO (si merge vers main)
* [ ] Pas d’erreurs (console/logs)
* [ ] Tests associés OK (si ticket test)
* [ ] Documentation ajoutée/maj dans `/docs/`
* [ ] Notification envoyée sur Discord (ticket/PR/merge)

---

# 8) Organisation du travail (13h → 18h)

Objectif : finir **US5, US6, US7, US8**.

Recommandation de découpage :

* **Dev A** : US6 catégorisation (base tomate/crème) + modèle data
* **Dev B** : US7 filtres (UI + logique)
* **Dev C** : US8 Playwright E2E (setup + tests)
* **Dev D** : US5 tests applicatifs (si décidé) + CI simple (optionnel)

Scrum Master :

* suit le board / issues
* enlève blocages
* force l’application des règles Git + DoD
* s’assure que Discord est alimenté

---

## 9) Rappels importants (anti-problèmes)

* Si `main` bouge : `git pull` **avant** de continuer / ouvrir PR
* Si conflits : on résout en équipe, pas en solo si ça touche plusieurs features
* Pas de secrets dans Git (`.env.local` ignoré, privilégier `.env.example`)

---

