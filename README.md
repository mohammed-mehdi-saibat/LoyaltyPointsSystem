FacileAchat - Système de Fidélité High-Tech
📝 Description

FacileAchat est une plateforme e-commerce complète intégrant un système de fidélité automatisé. Le projet permet aux utilisateurs de s'authentifier, d'acheter des produits pour accumuler des points, et de convertir ces points en récompenses tangibles (bons de réduction). Il inclut également une interface d'administration pour la gestion des données.
✨ Fonctionnalités Principales
🛒 Expérience Utilisateur (Client)

    Système d'Authentification : Inscription et connexion sécurisées avec hachage des mots de passe.

    Boutique Interactive : Catalogue de produits avec calcul de points en temps réel (Règle : 10 pts par tranche de 100$).

    Tableau de Bord : Visualisation du solde de points et historique complet des transactions.

    Système de Récompenses : Conversion de 500 points en un code promotionnel de 5$.

    Utilisation de Coupons : Application de codes promos lors de l'achat avec mise à jour automatique du prix final.

🛡️ Administration

    Monitoring Global : Vue d'ensemble de tous les clients et de leurs soldes.

    Audit des Vouchers : Suivi des codes générés et de leur statut (Actif ou Utilisé).

    Gestion des Comptes : Possibilité de supprimer des utilisateurs avec suppression en cascade des données associées.

    Sécurité des Rôles : Accès restreint aux routes administratives via vérification du rôle en session.

🛠️ Stack Technique

    Backend : PHP 8.x (Architecture MVC)

    Frontend : Twig (Moteur de templates) & CSS3 Custom

    Base de données : MySQL / MariaDB

    Gestion des dépendances : Composer

📂 Structure du Projet

    /public : Point d'entrée de l'application (index.php, CSS, JS).

    /src/Controllers : Logique de traitement des requêtes (Shop, Rewards, Admin, Auth).

    /src/Models : Interactions avec la base de données et logique métier.

    /templates : Fichiers Twig pour le rendu visuel.

    /vendor : Dépendances installées via Composer.

⚙️ Installation

    Cloner le projet dans votre dossier htdocs (XAMPP/WAMP).

    Base de données :

        Créer une base de données nommée facileachat.

        Importer le fichier SQL fourni (ou exécuter les scripts de création des tables users, points_transactions, vouchers).

    Configuration :

        Vérifier les identifiants de connexion dans src/Models/Database.php.

    Lancer l'application :

        Accéder à http://localhost/FacileAchat/public/.

⚖️ Règles Métier (Loyalty Rules)

    Gain de points : Total Points = floor(Prix / 100) * 10.

    Seuil de récompense : 500 points requis pour un bon de 5$.

    Validité : Un code promo est à usage unique et rattaché à un compte utilisateur spécifique.

👨‍💻 Développeur

    Projet : FacileAchat Loyalty Program

    Version : 1.0.0

    Environnement : Développement (XAMPP)
