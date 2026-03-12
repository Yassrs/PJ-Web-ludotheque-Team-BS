<?php
/**
 * Helpers d'authentification et d'autorisation
 */

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function currentUser() {
    if (!isLoggedIn()) return null;
    return $_SESSION['user'] ?? null;
}

function currentUserId() {
    return $_SESSION['user_id'] ?? null;
}

function currentUserRole() {
    return $_SESSION['user']['role'] ?? null;
}

function isMembre() {
    $role = currentUserRole();
    return in_array($role, [ROLE_MEMBRE, ROLE_ADMIN, ROLE_PRESIDENT]);
}

function isNonMembre() {
    return currentUserRole() === ROLE_NON_MEMBRE;
}

function isAdmin() {
    $role = currentUserRole();
    return in_array($role, [ROLE_ADMIN, ROLE_PRESIDENT]);
}

function isPresident() {
    return currentUserRole() === ROLE_PRESIDENT;
}

/**
 * Vérifie que l'utilisateur est connecté, sinon redirige
 */
function requireLogin() {
    if (!isLoggedIn()) {
        setFlash('warning', 'Vous devez être connecté pour accéder à cette page.');
        redirect('/connexion');
    }
}

/**
 * Vérifie que l'utilisateur est admin, sinon erreur 403
 */
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        http_response_code(403);
        require BASE_PATH . '/views/errors/403.php';
        exit;
    }
}

/**
 * Vérifie que l'utilisateur est président, sinon erreur 403
 */
function requirePresident() {
    requireLogin();
    if (!isPresident()) {
        http_response_code(403);
        require BASE_PATH . '/views/errors/403.php';
        exit;
    }
}

/**
 * Connecter un utilisateur
 */
function loginUser($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user'] = [
        'id' => $user['id'],
        'nom' => $user['nom'],
        'prenom' => $user['prenom'],
        'email' => $user['email'],
        'role' => $user['role'],
    ];
}

/**
 * Déconnecter l'utilisateur
 */
function logoutUser() {
    $_SESSION = [];
    session_destroy();
}

/**
 * Redirection
 */
function redirect($url) {
    header("Location: " . SITE_URL . $url);
    exit;
}
