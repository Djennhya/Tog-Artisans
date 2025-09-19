# Artisapp

Artisapp est une application web conçue pour valoriser la vente des produits artisanaux locaux. 
Elle permet aux artisans de présenter et vendre leurs créations, tout en offrant aux utilisateurs 
la possibilité de découvrir et d’acheter des produits uniques et faits main. Le but d’Artisapp est 
de soutenir l'artisanat local et de faciliter la mise en relation entre artisans et acheteurs.

## Fonctionnalités principales

- **Catalogue de produits artisanaux** : Découvrez une grande variété de produits locaux, classés par catégories.
- **Gestion des comptes artisans** : Les artisans peuvent créer un profil, ajouter leurs produits, gérer leur stock et suivre leurs ventes.
- **Achat en ligne** : Les utilisateurs peuvent acheter des produits directement via la plateforme.
- **Recherche et filtres** : Trouvez facilement des produits grâce à des filtres par catégorie, prix, popularité, etc.
- **Gestion des commandes** : Suivi des commandes, notifications pour les artisans et les clients.
- **Interface d’administration** : Gestion des utilisateurs, produits, commandes, et statistiques de la plateforme.

## Architecture

L’architecture d’Artisapp s’appuie sur le framework Laravel côté backend, associé à un frontend en Blade.

```
artisapp/
├── app/                # Logique métier Laravel (Contrôleurs, Modèles, Services)
├── bootstrap/          # Chargement de l’application
├── config/             # Fichiers de configuration
├── database/           # Migrations, seeders, factories
├── public/             # Fichiers accessibles publiquement (index.php, assets)
├── resources/
│   ├── views/          # Vues Blade (ou composants JS si SPA)
│   └── js/             # Frontend JS (Vue, React, etc.)
├── routes/             # Fichiers de routes web et API
├── storage/            # Fichiers générés (logs, cache, uploads)
├── tests/              # Tests automatisés
├── .env.example        # Exemple de configuration d’environnement
├── composer.json       # Dépendances PHP
├── package.json        # Dépendances JS
└── artisan             # CLI Laravel
```

### Schéma simplifié

- **Frontend** : Blade, Vue.js ou React
- **Backend** : API REST Laravel (contrôleurs, modèles Eloquent)
- **Authentification** : Laravel Breeze, Jetstream ou Sanctum
- **Base de données** : MySQL

### Modules principaux

- `User` (utilisateur/artisan/admin)
- `Product` (produit artisanal)
- `Order` (commande)
- `Category` (catégorie de produit)
- `Dashboard` (statistiques, gestion)

## Technologies utilisées

- **Backend** : [Laravel](https://laravel.com/) (PHP)
- **Base de données** : MySQL
- **Frontend** : Blade (Laravel) ou tout framework JS moderne au choix (Vue.js, React, etc.)
- **Authentification** : Laravel Breeze, Jetstream ou Sanctum
- **Déploiement** : Compatible avec tout hébergement supportant PHP et MySQL

## Installation

1. **Cloner le dépôt**
   ```bash
   git clone https://github.com/Djennhya/Artisapp.git
   cd Artisapp
   ```

2. **Installer les dépendances**
   ```bash
   composer install
   npm install && npm run dev
   ```

3. **Configurer l'environnement**
   - Copier `.env.example` en `.env` puis configurer les accès à la base de données MySQL.
   - Générer la clé d’application :
     ```bash
     php artisan key:generate
     ```

4. **Créer la base de données et lancer les migrations**
   ```bash
   php artisan migrate --seed
   ```

5. **Lancer le serveur de développement**
   ```bash
   php artisan serve
   ```

## Contribution

Les contributions sont les bienvenues ! Merci de créer une issue ou une pull request pour toute proposition d’amélioration ou de correction.

## Licence

Ce projet est sous licence MIT.

---

**Artisapp – Valorisons l’artisanat local !**
```
