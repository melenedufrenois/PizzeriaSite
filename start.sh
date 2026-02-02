#!/bin/bash
echo "======================================"
echo " 🍕 PIZZERIA - Démarrage Docker"
echo "======================================"
echo ""

# Arrêter les anciens conteneurs
echo "[1/5] Arrêt des anciens conteneurs..."
docker compose down

# Construire les images
echo "[2/5] Construction des images Docker..."
docker compose build

# Démarrer les conteneurs
echo "[3/5] Démarrage des conteneurs..."
docker compose up -d

# Attendre que tout soit prêt
echo "[4/5] Attente du démarrage (30 secondes)..."
sleep 30

# Initialiser la BDD
echo "[5/5] Initialisation de la base de données..."
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction
docker compose exec php php bin/console doctrine:fixtures:load --no-interaction
docker compose exec php php bin/console tailwind:build

echo ""
echo "======================================"
echo " ✅ PIZZERIA EST PRÊTE !"
echo "======================================"
echo ""
echo " 🌐 Application: http://localhost:8000"
echo " 📊 phpMyAdmin:  http://localhost:8080"
echo ""
echo " Pour arrêter: docker compose down"
echo "======================================"
