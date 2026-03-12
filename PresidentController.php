<?php
class PresidentController {

    public function __construct() {
        requirePresident();
    }

    public function bureau() {
        $bureauModel = new Bureau();
        $membres = $bureauModel->getAll();
        $dateFin = $bureauModel->getEndDate();
        
        $joursRestants = null;
        if ($dateFin) {
            $diff = (new DateTime())->diff(new DateTime($dateFin));
            $joursRestants = $diff->invert ? 0 : $diff->days;
        }

        $userModel = new Utilisateur();
        $admins = $userModel->getAdmins();

        $pageTitle = 'Gestion du Bureau';
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/president/bureau.php';
        require BASE_PATH . '/views/layout/footer.php';
    }

    public function ajouterAdmin() {
        $userModel = new Utilisateur();
        $utilisateurs = $userModel->getAllNonAdmin();

        $pageTitle = 'Ajouter un Administrateur';
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/president/ajouter_admin.php';
        require BASE_PATH . '/views/layout/footer.php';
    }

    public function doAjouterAdmin() {
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token invalide.');
            redirect('/president');
        }

        $userId = intval($_POST['id_utilisateur'] ?? 0);
        $roleBureau = sanitize($_POST['role_bureau'] ?? '');
        $dateDebut = sanitize($_POST['date_debut_mandat'] ?? date('Y-m-d'));
        $dateFin = sanitize($_POST['date_fin_mandat'] ?? date('Y-m-d', strtotime('+1 year')));

        if (!$userId || !$roleBureau) {
            setFlash('error', 'Veuillez remplir tous les champs.');
            redirect('/president/ajouter-admin');
        }

        // Changer le rôle utilisateur en admin
        $userModel = new Utilisateur();
        $userModel->updateRole($userId, ROLE_ADMIN);

        // Ajouter au bureau
        $bureauModel = new Bureau();
        $bureauModel->create([
            'id_utilisateur' => $userId,
            'role_bureau' => $roleBureau,
            'date_debut_mandat' => $dateDebut,
            'date_fin_mandat' => $dateFin,
        ]);

        setFlash('success', 'L\'administrateur a été ajouté au bureau.');
        redirect('/president');
    }

    public function retirerAdmin($userId) {
        $userModel = new Utilisateur();
        $user = $userModel->findById($userId);

        if (!$user || $user['role'] === ROLE_PRESIDENT) {
            setFlash('error', 'Action impossible.');
            redirect('/president');
        }

        // Remettre en tant que membre
        $userModel->updateRole($userId, ROLE_MEMBRE);

        // Désactiver dans le bureau
        $bureauModel = new Bureau();
        $bureauEntry = $bureauModel->findByUserId($userId);
        if ($bureauEntry) {
            $bureauModel->deactivate($bureauEntry['id']);
        }

        setFlash('success', $user['prenom'] . ' ' . $user['nom'] . ' a été retiré du bureau.');
        redirect('/president');
    }

    public function infos() {
        $bureauModel = new Bureau();
        $membres = $bureauModel->getAll();
        $dateFin = $bureauModel->getEndDate();

        $pageTitle = 'Informations Internes';
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/president/infos.php';
        require BASE_PATH . '/views/layout/footer.php';
    }
}
