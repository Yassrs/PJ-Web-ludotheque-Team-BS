<?php
session_start();

// Chargement des fichiers de configuration
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

// Chargement des helpers
require_once BASE_PATH . '/helpers/auth.php';
require_once BASE_PATH . '/helpers/flash.php';
require_once BASE_PATH . '/helpers/validation.php';

// Chargement des modèles
require_once BASE_PATH . '/models/Utilisateur.php';
require_once BASE_PATH . '/models/Jeu.php';
require_once BASE_PATH . '/models/Evenement.php';
require_once BASE_PATH . '/models/Demande.php';
require_once BASE_PATH . '/models/Bureau.php';
require_once BASE_PATH . '/models/MessageContact.php';
require_once BASE_PATH . '/models/VacancesUniversitaires.php';

// Token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function csrf_token() {
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function verify_csrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
