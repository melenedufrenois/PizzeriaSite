#!/bin/bash

# ===========================================
# Script de déploiement O'Trari Pizzeria
# Usage: ./deploy.sh [--with-fixtures]
# ===========================================

set -e  # Exit on error

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Config
SOURCE_DIR="/home/mehdi/PizzeriaSite"
TARGET_DIR="/var/www/pizzeria"
WITH_FIXTURES=false

# Parse arguments
if [[ "$1" == "--with-fixtures" ]]; then
    WITH_FIXTURES=true
fi

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}   Déploiement O'Trari Pizzeria${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Step 1: Git pull
echo -e "${YELLOW}[1/7] Pull des dernières modifications...${NC}"
cd "$SOURCE_DIR"
git pull

# Step 2: Rsync files
echo -e "${YELLOW}[2/7] Synchronisation des fichiers...${NC}"
sudo rsync -av --delete \
    --exclude '.env.local' \
    --exclude 'var/' \
    --exclude '.git/' \
    --exclude 'node_modules/' \
    "$SOURCE_DIR/" "$TARGET_DIR/"

# Step 3: Fix permissions
echo -e "${YELLOW}[3/7] Correction des permissions...${NC}"
sudo chown -R mehdi:mehdi "$TARGET_DIR"
sudo chmod -R 775 "$TARGET_DIR/var" 2>/dev/null || true

# Step 4: Install dependencies
echo -e "${YELLOW}[4/7] Installation des dépendances Composer...${NC}"
cd "$TARGET_DIR"
composer install --no-dev --optimize-autoloader

# Step 5: Database - always recreate schema (site vitrine, pas de données utilisateur)
echo -e "${YELLOW}[5/7] Rechargement de la base de données...${NC}"
php bin/console doctrine:schema:drop --force --env=prod || true
php bin/console doctrine:schema:create --env=prod
php bin/console doctrine:fixtures:load --no-interaction --env=prod

# Step 6: Clear cache & compile assets
echo -e "${YELLOW}[6/7] Compilation des assets...${NC}"
php bin/console cache:clear --env=prod
php bin/console asset-map:compile --env=prod
php bin/console importmap:install --env=prod

# Step 7: Build Tailwind
echo -e "${YELLOW}[7/7] Build Tailwind CSS...${NC}"
php bin/console tailwind:build --minify

# Fix var permissions again after builds
sudo chown -R mehdi:mehdi "$TARGET_DIR/var"
sudo chmod -R 775 "$TARGET_DIR/var"

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}   ✅ Déploiement terminé !${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "Site disponible sur: ${YELLOW}https://otrari.mehditrari.com${NC}"
