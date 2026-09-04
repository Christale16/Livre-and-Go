# Livre and Go

Plateforme Laravel de livraison avec espaces client, livreur et administrateur, commandes, messagerie et localisation.

## Prérequis Windows / XAMPP

- PHP 8.2.x ou supérieur compatible Laravel 12
- Composer 2.x
- MySQL/MariaDB via XAMPP

## Installation

1. Extraire le projet.
2. Ouvrir PowerShell dans le dossier du projet.
3. Installer les dépendances :

```powershell
composer install
```

4. Si `.env` n'existe pas :

```powershell
copy .env.example .env
```

5. Générer la clé uniquement si `.env` n'en contient pas déjà une :

```powershell
php artisan key:generate
```

6. Démarrer Apache et MySQL dans XAMPP.
7. Créer la base `livraben` dans phpMyAdmin, puis importer `database/livraben.sql`.
8. Vérifier la connexion MySQL dans `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=livraben
DB_USERNAME=root
DB_PASSWORD=
```

9. Nettoyer les caches Laravel :

```powershell
php artisan optimize:clear
```

10. Vérifier les migrations :

```powershell
php artisan migrate:status
```

> Si `database/livraben.sql` a déjà été importé, ne lance pas `php artisan migrate:fresh` : cette commande supprimerait les tables.

11. Lancer :

```powershell
php artisan serve
```

Puis ouvrir http://127.0.0.1:8000.

## Comptes de démonstration

- Admin : `admin@livraben.test` / `password`
- Client : `client@livraben.test` / `password`
- Livreur : `livreur@livraben.test` / `password`

## Base de données

Le fichier `database/livraben.sql` contient les tables `users`, `orders`, `messages`, `sessions`, `password_reset_tokens` et `migrations`, ainsi que les comptes de démonstration.
