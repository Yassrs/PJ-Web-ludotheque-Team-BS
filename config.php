<?php
// Configuration globale du site
define('SITE_NAME', 'Ludothèque');
define('SITE_URL', 'http://localhost/ludotheque');
define('BASE_PATH', dirname(__DIR__));

// Durées d'emprunt/location
define('DUREE_MIN_JOURS', 7);   // 1 semaine
define('DUREE_MAX_JOURS', 14);  // 2 semaines

// Réservations "Jeu du Jeudi"
define('MAX_JEUX_PAR_JEUDI', 5); // Nombre max de jeux réservables pour un même jeudi

// Upload d'images
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5 Mo
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('UPLOAD_DIR_JEUX', BASE_PATH . '/public/img/jeux/');
define('UPLOAD_DIR_EVENTS', BASE_PATH . '/public/img/evenements/');

// Rôles utilisateur
define('ROLE_NON_MEMBRE', 'non_membre');
define('ROLE_MEMBRE', 'membre');
define('ROLE_ADMIN', 'admin');
define('ROLE_PRESIDENT', 'president');

// Statuts jeux
define('STATUT_EN_STOCK', 'en_stock');
define('STATUT_EMPRUNTE', 'emprunte');
define('STATUT_LOUE', 'loue');
define('STATUT_PERDU', 'perdu');

// Statuts demandes
define('DEMANDE_EN_ATTENTE', 'en_attente');
define('DEMANDE_VALIDEE', 'validee');
define('DEMANDE_REFUSEE', 'refusee');

// Catégories événements
define('CATEGORIES_EVENEMENTS', ['salle_jeudi', 'jeu_jeudi', 'soiree_jeux', 'occasionnel']);
