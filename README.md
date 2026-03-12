# Ludothèque Projet Web APP 2026

## Description
Application web de gestion d'une ludothÃ¨que associative Ã©tudiante.
Gestion des jeux, emprunts, locations, rÃ©servations et Ã©vÃ©nements.

## Technologies
- **Frontend** : HTML5, CSS3, Bootstrap 5, JavaScript, jQuery, AJAX
- **Backend** : PHP 8 (MVC natif)
- **Base de donnÃ©es** : MySQL 8
- **Versioning** : Git

## Installation

1. **Cloner le projet** dans le dossier de votre serveur web (htdocs, www, etc.)
2. **Importer la base de données** :
   ```
   mysql -u root -p < sql/create_database.sql
   ```
3. **Configurer la connexion** dans `config/database.php` (host, user, pass)
4. **Configurer l'URL** dans `config/config.php` (SITE_URL)
5. **Activer le mod_rewrite** Apache pour le .htaccess

## Comptes de test

| Role | Email | Mot de passe |
|------|-------|-------------|
| Président | president@ludotheque.fr | password123 |
| Admin | admin1@ludotheque.fr | password123 |
| Membre | membre1@ludotheque.fr | password123 |
| Non-membre | user1@email.com | password123 |

## Structure du projet
```
ludotheque/
â”œâ”€â”€ config/          # Configuration (BDD, constantes, init)
â”œâ”€â”€ controllers/     # ContrÃ´leurs (logique mÃ©tier)
â”œâ”€â”€ models/          # ModÃ¨les (accÃ¨s BDD)
â”œâ”€â”€ views/           # Vues (templates HTML/PHP)
â”œâ”€â”€ public/          # Ressources statiques (CSS, JS, images)
â”œâ”€â”€ helpers/         # Fonctions utilitaires
â”œâ”€â”€ sql/             # Scripts SQL
â””â”€â”€ index.php        # Front Controller (routeur)
```


Sersar Yassine, Achache Camélia, El Bachir Malek




