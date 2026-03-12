<?php
/**
 * Front Controller - Point d'entrée unique
 * Ludothèque - Projet Web APP 2026
 */
require_once __DIR__ . '/config/init.php';

// Récupérer l'URL demandée
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url = filter_var($url, FILTER_SANITIZE_URL);
$parts = explode('/', $url);

$page = $parts[0] ?? '';
$action = $parts[1] ?? '';
$param = $parts[2] ?? '';

// Routage
switch ($page) {

    // === PAGE D'ACCUEIL ===
    case '':
    case 'accueil':
        require BASE_PATH . '/controllers/AccueilController.php';
        $ctrl = new AccueilController();
        $ctrl->index();
        break;

    // === AUTHENTIFICATION ===
    case 'connexion':
        require BASE_PATH . '/controllers/AuthController.php';
        $ctrl = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ctrl->doLogin();
        } else {
            $ctrl->login();
        }
        break;

    case 'inscription':
        require BASE_PATH . '/controllers/AuthController.php';
        $ctrl = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ctrl->doRegister();
        } else {
            $ctrl->register();
        }
        break;

    case 'deconnexion':
        require BASE_PATH . '/controllers/AuthController.php';
        $ctrl = new AuthController();
        $ctrl->logout();
        break;

    // === LUDOTHÈQUE (JEUX) ===
    case 'ludotheque':
        require BASE_PATH . '/controllers/JeuController.php';
        $ctrl = new JeuController();
        if ($action === 'jeu' && $param) {
            $ctrl->fiche($param);
        } elseif ($action === 'api') {
            // API AJAX pour les filtres
            $ctrl->api();
        } else {
            $ctrl->catalogue();
        }
        break;

    // === ÉVÉNEMENTS ===
    case 'evenements':
        require BASE_PATH . '/controllers/EvenementController.php';
        $ctrl = new EvenementController();
        if ($action && is_numeric($action)) {
            $ctrl->detail($action);
        } else {
            $ctrl->liste();
        }
        break;

    // === DEMANDES (emprunt, location, réservation) ===
    case 'demande':
        require BASE_PATH . '/controllers/DemandeController.php';
        $ctrl = new DemandeController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch ($action) {
                case 'emprunt':     $ctrl->emprunt($param); break;
                case 'location':    $ctrl->location($param); break;
                case 'reservation': $ctrl->reservation($param); break;
                default: redirect('/ludotheque');
            }
        } else {
            redirect('/ludotheque');
        }
        break;

    // === MON COMPTE ===
    case 'mon-compte':
        require BASE_PATH . '/controllers/CompteController.php';
        $ctrl = new CompteController();
        switch ($action) {
            case 'demandes': $ctrl->demandes(); break;
            case 'emprunts': $ctrl->emprunts(); break;
            default: $ctrl->profil();
        }
        break;

    // === ADMINISTRATION ===
    case 'admin':
        require BASE_PATH . '/controllers/AdminController.php';
        $ctrl = new AdminController();
        switch ($action) {
            case '':
            case 'dashboard':
                $ctrl->dashboard();
                break;
            case 'jeux':
                if ($param === 'ajouter') {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') $ctrl->doAjouterJeu();
                    else $ctrl->ajouterJeu();
                } elseif ($param === 'modifier' && isset($parts[3])) {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') $ctrl->doModifierJeu($parts[3]);
                    else $ctrl->modifierJeu($parts[3]);
                } elseif ($param === 'supprimer' && isset($parts[3])) {
                    $ctrl->supprimerJeu($parts[3]);
                } else {
                    $ctrl->listeJeux();
                }
                break;
            case 'evenements':
                if ($param === 'ajouter') {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') $ctrl->doAjouterEvenement();
                    else $ctrl->ajouterEvenement();
                } elseif ($param === 'modifier' && isset($parts[3])) {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') $ctrl->doModifierEvenement($parts[3]);
                    else $ctrl->modifierEvenement($parts[3]);
                } elseif ($param === 'supprimer' && isset($parts[3])) {
                    $ctrl->supprimerEvenement($parts[3]);
                } else {
                    $ctrl->listeEvenements();
                }
                break;
            case 'demandes':
                if ($param === 'accepter' && isset($parts[3])) {
                    $ctrl->accepterDemande($parts[3]);
                } elseif ($param === 'refuser' && isset($parts[3])) {
                    $ctrl->refuserDemande($parts[3]);
                } else {
                    $ctrl->listeDemandes();
                }
                break;
            default:
                $ctrl->dashboard();
        }
        break;

    // === PRÉSIDENT ===
    case 'president':
        require BASE_PATH . '/controllers/PresidentController.php';
        $ctrl = new PresidentController();
        switch ($action) {
            case 'ajouter-admin':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') $ctrl->doAjouterAdmin();
                else $ctrl->ajouterAdmin();
                break;
            case 'retirer-admin':
                if ($param) $ctrl->retirerAdmin($param);
                break;
            case 'infos':
                $ctrl->infos();
                break;
            default:
                $ctrl->bureau();
        }
        break;

    // === PAGES STATIQUES ===
    case 'a-propos':
        require BASE_PATH . '/controllers/ContactController.php';
        $ctrl = new ContactController();
        $ctrl->apropos();
        break;

    case 'contact':
        require BASE_PATH . '/controllers/ContactController.php';
        $ctrl = new ContactController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ctrl->doContact();
        } else {
            $ctrl->contact();
        }
        break;

    // === 404 ===
    default:
        http_response_code(404);
        $pageTitle = 'Page non trouvée';
        require BASE_PATH . '/views/errors/404.php';
        break;
}
