MyMangaLibrary
Application web de gestion de bibliothèque de mangas personnelle, développée dans le cadre du Projet Fil Rouge.

Présentation
MyMangaLibrary permet à chaque utilisateur de : 
    - Consulter un catalogue global de mangas
    - Ajouter des mangas à sa bibliothèque personnelle
    - Suivre son avancement de lecture (à lire, en lecture, lu)
    - Gérer le catalogue (administrateurs uniquement)

Stack technique
Couche          Technologie
Backend         PHP 8.3 + Symfony 7
ORM             Doctrine
Base de données MariaDB 10.6
Frontend        Twig + Bootstrap 5
Environnement   Docker + docker-compose
Tests           PHPUnit 12

Installation et lancement
Prérequis

Docker Desktop installé et lancé
Git installé

Lancer les conteneurs Docker 
(Fichier Makefile)
build:
	docker compose --progress=plain build

up:
	docker compose up -d

exec:
	docker compose exec apache /bin/bash

init-symfony:
	docker compose exec apache sh -c "./init-symfony.sh"

Accéder à l'application
Ouvrir http://localhost:8000 dans le navigateur.

Créer un compte utilisateur
Aller sur http://localhost:8000/register et créer un compte.

Créer un compte administrateur
Après inscription, aller sur http://localhost:8080 (phpMyAdmin) :

    Serveur : database
    Utilisateur : app
    Mot de passe : app_password

    Puis mettre le rôle Admin (["ROLE_ADMIN"])

Tests 
In order to run the tests : docker-compose exec apache php bin/phpunit tests/Entity/
Or docker-compose exec apache php bin/phpunit tests/Controller/MangaControllerTest.php (for example. etc...) 
(fichier tests)

Voici quelques Commandes utiles : 
Arrêter les conteneurs
docker-compose down

Voir les logs Apache
docker-compose logs apache

Accéder au shell du conteneur
docker-compose exec apache bash

Créer une migration après modification d'entité
docker-compose exec apache php bin/console make:migration

Vider le cache Symfony
docker-compose exec apache php bin/console cache:clear
