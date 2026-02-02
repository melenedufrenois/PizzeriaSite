#!/bin/bash
set -e

echo "🍕 Initialisation de Pizzeria..."

# Attendre que MySQL soit prêt
echo "⏳ Attente de MySQL..."
while ! mysqladmin ping -h"database" --silent; do
    sleep 1
done
echo "✅ MySQL est prêt!"

# Installation des dépendances si nécessaire
if [ ! -d "vendor" ]; then
    echo "📦 Installation des dépendances Composer..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Créer la base de données si elle n'existe pas
echo "🗄️ Configuration de la base de données..."
php bin/console doctrine:database:create --if-not-exists --no-interaction

# Exécuter les migrations
echo "📝 Exécution des migrations..."
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

# Charger les fixtures si la table products est vide
PRODUCTS_COUNT=$(php bin/console doctrine:query:sql "SELECT COUNT(*) FROM products" 2>/dev/null | grep -o '[0-9]*' | head -1 || echo "0")
if [ "$PRODUCTS_COUNT" = "0" ] || [ -z "$PRODUCTS_COUNT" ]; then
    echo "🌱 Chargement des fixtures..."
    php bin/console doctrine:fixtures:load --no-interaction
fi

# Build Tailwind CSS
echo "🎨 Build Tailwind CSS..."
php bin/console tailwind:build

# Vider le cache
echo "🧹 Nettoyage du cache..."
php bin/console cache:clear

echo "✅ Pizzeria est prête!"
echo "🌐 Accès: http://localhost:8000"
echo "📊 phpMyAdmin: http://localhost:8080"

# Démarrer PHP-FPM
exec php-fpm
