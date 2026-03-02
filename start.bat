@echo off
echo ======================================
echo  🍕 PIZZERIA - Demarrage Docker
echo ======================================
echo.

REM Arrêter les anciens conteneurs
echo [1/5] Arret des anciens conteneurs...
docker compose down

REM Construire les images
echo [2/5] Construction des images Docker...
docker compose build

REM Démarrer les conteneurs
echo [3/5] Demarrage des conteneurs...
docker compose up -d

REM Attendre que tout soit prêt
echo [4/5] Attente du demarrage (30 secondes)...
timeout /t 30 /nobreak > nul

REM Initialiser la BDD
echo [5/5] Initialisation de la base de donnees...
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction
docker compose exec php php bin/console doctrine:fixtures:load --no-interaction
docker compose exec php php bin/console tailwind:build

echo.
echo ======================================
echo  ✅ PIZZERIA EST PRETE !
echo ======================================
echo.
echo  🌐 Application: http://localhost:8000
echo  📊 phpMyAdmin:  http://localhost:8080
echo.
echo  Pour arreter: docker compose down
echo ======================================
pause
