<?php
class JeuController {

    public function catalogue() {
        $jeuModel = new Jeu();
        $filters = [
            'nb_joueurs' => $_GET['nb_joueurs'] ?? '',
            'difficulte_apprentissage' => $_GET['difficulte_apprentissage'] ?? '',
            'difficulte_jeu' => $_GET['difficulte_jeu'] ?? '',
            'temps_min' => $_GET['temps_min'] ?? '',
            'temps_max' => $_GET['temps_max'] ?? '',
            'recherche' => $_GET['recherche'] ?? '',
            'tri' => $_GET['tri'] ?? 'nom',
            'ordre' => $_GET['ordre'] ?? 'ASC',
        ];
        $jeux = $jeuModel->getAll($filters);

        $pageTitle = 'Ludothèque';
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/jeux/catalogue.php';
        require BASE_PATH . '/views/layout/footer.php';
    }

    public function fiche($id) {
        $jeuModel = new Jeu();
        $jeu = $jeuModel->findById($id);

        if (!$jeu) {
            http_response_code(404);
            $pageTitle = 'Jeu non trouvé';
            require BASE_PATH . '/views/errors/404.php';
            return;
        }

        // Vérifier si l'utilisateur a déjà une demande active
        $hasActiveRequest = false;
        if (isLoggedIn()) {
            $demandeModel = new Demande();
            $hasActiveRequest = $demandeModel->hasActiveRequest(currentUserId(), $id);
        }

        $pageTitle = $jeu['nom'];
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/jeux/fiche.php';
        require BASE_PATH . '/views/layout/footer.php';
    }

    /**
     * API AJAX pour le filtrage dynamique
     */
    public function api() {
        header('Content-Type: application/json');
        $jeuModel = new Jeu();
        $filters = [
            'nb_joueurs' => $_GET['nb_joueurs'] ?? '',
            'difficulte_apprentissage' => $_GET['difficulte_apprentissage'] ?? '',
            'difficulte_jeu' => $_GET['difficulte_jeu'] ?? '',
            'temps_min' => $_GET['temps_min'] ?? '',
            'temps_max' => $_GET['temps_max'] ?? '',
            'recherche' => $_GET['recherche'] ?? '',
            'tri' => $_GET['tri'] ?? 'nom',
            'ordre' => $_GET['ordre'] ?? 'ASC',
        ];
        $jeux = $jeuModel->getAll($filters);
        echo json_encode(['success' => true, 'jeux' => $jeux, 'count' => count($jeux)]);
        exit;
    }
}
