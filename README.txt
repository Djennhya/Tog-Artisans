# Tog'Artisans

Tog'Artisans est une plateforme e-commerce intelligente dédiée à la digitalisation et à la valorisation de l’artisanat local. Grâce aux technologies émergentes telles que la réalité augmentée pour visualiser les produits, l’authentification forte (2FA) pour la sécurité des comptes, et un chatbot pour accompagner les clients dans leurs choix, elle offre une expérience innovante et sécurisée à la fois pour les artisans et les acheteurs.

## Fonctionnalités principales

- Catalogue des produits artisanaux (par catégorie)
- Création de compte artisan et gestion du profil
- Ajout et gestion des produits (stock, prix, description)
- Achat en ligne et gestion des commandes
- Recherche et filtres simples
- Interface d’administration basique (gestion des utilisateurs, commandes, produits)

## Structure du projet

```
TogArtisans/
├── config/           # Fichiers de configuration (connexion BDD, etc.)
├── public/           # Fichiers accessibles publiquement (index.php, assets, images, css, js)
├── src/              # Code source PHP (classes, fonctions, logique métier)
├── templates/        # Fichiers HTML ou partials PHP pour l'affichage
├── uploads/          # Fichiers envoyés par les utilisateurs (photos produits)
├── .env.example      # Exemple de configuration d’environnement
└── README.md
```

## Installation

1. **Cloner le dépôt**
   ```bash
   git clone https://github.com/Djennhya/TogArtisans.git
   cd TogArtisans
   ```

2. **Configurer la base de données**
   - Créez une base de données MySQL.
   - Configurez les accès dans le fichier `config/database.php` ou `.env`.

3. **Lancer l'application**
   - Placez le dossier sur un serveur web (ex : XAMPP, WAMP, MAMP, ou un hébergement PHP/MySQL).
   - Accédez à `http://localhost/TogArtisans/public` dans votre navigateur.

4. **(Optionnel) Importer la structure de la base de données**
   - Un fichier `.sql` peut être fourni dans le dossier `database/` pour créer les tables nécessaires.

## Prérequis

- PHP 7.4 ou supérieur
- Serveur web (Apache, Nginx…)
- MySQL ou MariaDB

## Contribution

Les contributions sont les bienvenues ! Merci de créer une issue ou une pull request pour toute proposition d’amélioration ou de correction.


**Tog'Artisans – Valorisons l’artisanat local !**
