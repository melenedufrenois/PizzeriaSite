#!/bin/bash
# ============================================
# 🚀 Script de déploiement PRODUCTION
# Optimisé pour serveur 2Go RAM
# ============================================

set -e

echo "🚀 Déploiement Pizzeria Site - Production"
echo "=========================================="
echo ""

# Vérifier la RAM disponible
echo "📊 Vérification de la mémoire..."
free -h
echo ""

# Arrêter les anciens conteneurs
echo "🛑 Arrêt des anciens conteneurs..."
docker compose -f compose.prod.yaml down --remove-orphans 2>/dev/null || true

# Nettoyer les ressources inutilisées
echo "🧹 Nettoyage Docker..."
docker system prune -f

# Construire l'image
echo "🔨 Construction de l'image PHP..."
docker compose -f compose.prod.yaml build --no-cache

# Démarrer les services
echo "🚀 Démarrage des services..."
docker compose -f compose.prod.yaml up -d

# Attendre que MySQL soit prêt
echo "⏳ Attente de MySQL (60 secondes max)..."
sleep 10
timeout=60
counter=0
until docker compose -f compose.prod.yaml exec -T database mysqladmin ping -h localhost --silent 2>/dev/null; do
    counter=$((counter+1))
    if [ $counter -ge $timeout ]; then
        echo "❌ MySQL n'a pas démarré à temps"
        exit 1
    fi
    echo "  Attente... ($counter/$timeout)"
    sleep 1
done
echo "✅ MySQL est prêt!"

# Exécuter les migrations
echo "📦 Exécution des migrations..."
docker compose -f compose.prod.yaml exec -T php php bin/console doctrine:migrations:migrate --no-interaction

# Charger les fixtures (optionnel - commenter pour la vraie prod)
echo "🌱 Chargement des données de démo..."
docker compose -f compose.prod.yaml exec -T php php bin/console doctrine:fixtures:load --no-interaction

# Compiler les assets
echo "🎨 Compilation Tailwind CSS..."
docker compose -f compose.prod.yaml exec -T php php bin/console tailwind:build --minify

# Vider le cache
echo "🗑️ Vidage du cache Symfony..."
docker compose -f compose.prod.yaml exec -T php php bin/console cache:clear --env=prod

# Warmup du cache
echo "🔥 Warmup du cache..."
docker compose -f compose.prod.yaml exec -T php php bin/console cache:warmup --env=prod

# Afficher le statut
echo ""
echo "=========================================="
echo "✅ Déploiement terminé!"
echo "=========================================="
echo ""
docker compose -f compose.prod.yaml ps
echo ""
echo "📊 Utilisation mémoire des conteneurs:"
docker stats --no-stream --format "table {{.Name}}\t{{.MemUsage}}\t{{.MemPerc}}"
echo ""
echo "🌐 Site accessible sur: http://$(hostname -I | awk '{print $1}'):80"
echo ""
