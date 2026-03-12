<?php
class AdminController {

    public function __construct() {
        requireAdmin();
    }

    // ========== DASHBOARD ==========
    public function dashboard() {
        $jeuModel = new Jeu();
        $demandeModel = new Demande();
        $evenementModel = new Evenement();

        $stats = $jeuModel->countByStatut();
        $pendingCount = $demandeModel->countPending();
        $upcomingEvents = $evenementModel->countUpcoming();
        $pendingDemandes = $demandeModel->getPending();

        $pageTitle = 'Administration';
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/admin/dashboard.php';
        require BASE_PATH . '/views/layout/footer.php';
    }

    // ========== GESTION DES JEUX ==========
    public function listeJeux() {
        $jeuModel = new Jeu();
        $jeux = $jeuModel->getAll();
        $pageTitle = 'Gestion des Jeux';
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/admin/jeux/liste.php';
        require BASE_PATH . '/views/layout/footer.php';
    }

    public function ajouterJeu() {
        $pageTitle = 'Ajouter un Jeu';
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/admin/jeux/ajouter.php';
        require BASE_PATH . '/views/layout/footer.php';
    }

    public function doAjouterJeu() {
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token de sécurité invalide.');
            redirect('/admin/jeux/ajouter');
        }

        $data = [
            'nom' => sanitize($_POST['nom'] ?? ''),
            'description' => sanitize($_POST['description'] ?? ''),
            'nb_joueurs_min' => intval($_POST['nb_joueurs_min'] ?? 1),
            'nb_joueurs_max' => intval($_POST['nb_joueurs_max'] ?? 4),
            'difficulte_apprentissage' => sanitize($_POST['difficulte_apprentissage'] ?? 'moyen'),
            'difficulte_jeu' => sanitize($_POST['difficulte_jeu'] ?? 'moyen'),
            'temps_jeu_minutes' => intval($_POST['temps_jeu_minutes'] ?? 30),
            'regles' => sanitize($_POST['regles'] ?? ''),
            'statut' => STATUT_EN_STOCK,
        ];

        // Upload image
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imgErrors = validateImage($_FILES['image']);
            if (!empty($imgErrors)) {
                setFlash('error', implode('<br>', $imgErrors));
                redirect('/admin/jeux/ajouter');
            }
            $data['image'] = uploadImage($_FILES['image'], UPLOAD_DIR_JEUX);
        }

        $errors = validateRequired(['nom' => 'Nom du jeu'], $data);
        if (!empty($errors)) {
            setFlash('error', implode('<br>', $errors));
            redirect('/admin/jeux/ajouter');
        }

        $jeuModel = new Jeu();
        $jeuModel->create($data);
        setFlash('success', 'Le jeu « ' . $data['nom'] . ' » a été ajouté.');
        redirect('/admin/jeux');
    }

    public function modifierJeu($id) {
        $jeuModel = new Jeu();
        $jeu = $jeuModel->findById($id);
        if (!$jeu) { setFlash('error', 'Jeu introuvable.'); redirect('/admin/jeux'); }

        $pageTitle = 'Modifier : ' . $jeu['nom'];
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/admin/jeux/modifier.php';
        require BASE_PATH . '/views/layout/footer.php';
    }

    public function doModifierJeu($id) {
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token invalide.'); redirect('/admin/jeux');
        }

        $data = [
            'nom' => sanitize($_POST['nom'] ?? ''),
            'description' => sanitize($_POST['description'] ?? ''),
            'nb_joueurs_min' => intval($_POST['nb_joueurs_min'] ?? 1),
            'nb_joueurs_max' => intval($_POST['nb_joueurs_max'] ?? 4),
            'difficulte_apprentissage' => sanitize($_POST['difficulte_apprentissage'] ?? 'moyen'),
            'difficulte_jeu' => sanitize($_POST['difficulte_jeu'] ?? 'moyen'),
            'temps_jeu_minutes' => intval($_POST['temps_jeu_minutes'] ?? 30),
            'regles' => sanitize($_POST['regles'] ?? ''),
            'statut' => sanitize($_POST['statut'] ?? STATUT_EN_STOCK),
        ];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imgErrors = validateImage($_FILES['image']);
            if (empty($imgErrors)) {
                $data['image'] = uploadImage($_FILES['image'], UPLOAD_DIR_JEUX);
            }
        }

        $jeuModel = new Jeu();
        $jeuModel->update($id, $data);
        setFlash('success', 'Le jeu a été modifié.');
        redirect('/admin/jeux');
    }

    public function supprimerJeu($id) {
        $jeuModel = new Jeu();
        $jeu = $jeuModel->findById($id);
        if ($jeu) {
            $jeuModel->delete($id);
            setFlash('success', 'Le jeu « ' . $jeu['nom'] . ' » a été supprimé.');
        }
        redirect('/admin/jeux');
    }

    // ========== GESTION DES ÉVÉNEMENTS ==========
    public function listeEvenements() {
        $evenementModel = new Evenement();
        $evenements = $evenementModel->getAll();
        $pageTitle = 'Gestion des Événements';
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/admin/evenements/liste.php';
        require BASE_PATH . '/views/layout/footer.php';
    }

    public function ajouterEvenement() {
        $pageTitle = 'Ajouter un Événement';
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/admin/evenements/ajouter.php';
        require BASE_PATH . '/views/layout/footer.php';
    }

    public function doAjouterEvenement() {
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token invalide.'); redirect('/admin/evenements/ajouter');
        }

        $data = [
            'titre' => sanitize($_POST['titre'] ?? ''),
            'description' => sanitize($_POST['description'] ?? ''),
            'date_evenement' => sanitize($_POST['date_evenement'] ?? ''),
            'heure' => sanitize($_POST['heure'] ?? ''),
            'lieu' => sanitize($_POST['lieu'] ?? ''),
            'categorie' => sanitize($_POST['categorie'] ?? ''),
            'id_createur' => currentUserId(),
        ];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imgErrors = validateImage($_FILES['image']);
            if (empty($imgErrors)) {
                $data['image'] = uploadImage($_FILES['image'], UPLOAD_DIR_EVENTS);
            }
        }

        $evenementModel = new Evenement();
        $evenementModel->create($data);
        setFlash('success', 'L\'événement a été créé.');
        redirect('/admin/evenements');
    }

    public function modifierEvenement($id) {
        $evenementModel = new Evenement();
        $evenement = $evenementModel->findById($id);
        if (!$evenement) { setFlash('error', 'Événement introuvable.'); redirect('/admin/evenements'); }

        $pageTitle = 'Modifier : ' . $evenement['titre'];
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/admin/evenements/modifier.php';
        require BASE_PATH . '/views/layout/footer.php';
    }

    public function doModifierEvenement($id) {
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token invalide.'); redirect('/admin/evenements');
        }

        $data = [
            'titre' => sanitize($_POST['titre'] ?? ''),
            'description' => sanitize($_POST['description'] ?? ''),
            'date_evenement' => sanitize($_POST['date_evenement'] ?? ''),
            'heure' => sanitize($_POST['heure'] ?? ''),
            'lieu' => sanitize($_POST['lieu'] ?? ''),
            'categorie' => sanitize($_POST['categorie'] ?? ''),
        ];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imgErrors = validateImage($_FILES['image']);
            if (empty($imgErrors)) {
                $data['image'] = uploadImage($_FILES['image'], UPLOAD_DIR_EVENTS);
            }
        }

        $evenementModel = new Evenement();
        $evenementModel->update($id, $data);
        setFlash('success', 'L\'événement a été modifié.');
        redirect('/admin/evenements');
    }

    public function supprimerEvenement($id) {
        $evenementModel = new Evenement();
        $evenementModel->delete($id);
        setFlash('success', 'L\'événement a été supprimé.');
        redirect('/admin/evenements');
    }

    // ========== TRAITEMENT DES DEMANDES ==========
    public function listeDemandes() {
        $demandeModel = new Demande();
        $filters = [
            'statut' => $_GET['statut'] ?? '',
            'type_demande' => $_GET['type'] ?? '',
        ];
        $demandes = $demandeModel->getAll($filters);

        $pageTitle = 'Traitement des Demandes';
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/admin/demandes/liste.php';
        require BASE_PATH . '/views/layout/footer.php';
    }

    public function accepterDemande($id) {
        $demandeModel = new Demande();
        if ($demandeModel->accept($id, currentUserId())) {
            setFlash('success', 'La demande a été acceptée.');
        } else {
            setFlash('error', 'Erreur lors du traitement.');
        }
        redirect('/admin/demandes');
    }

    public function refuserDemande($id) {
        $motif = sanitize($_POST['motif_refus'] ?? $_GET['motif'] ?? '');
        $demandeModel = new Demande();
        $demandeModel->reject($id, currentUserId(), $motif);
        setFlash('success', 'La demande a été refusée.' . ($motif ? ' Motif : ' . $motif : ''));
        redirect('/admin/demandes');
    }
}
