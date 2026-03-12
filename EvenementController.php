<?php
class EvenementController {

    public function liste() {
        $evenementModel = new Evenement();
        $filters = [
            'categorie' => $_GET['categorie'] ?? '',
            'periode' => $_GET['periode'] ?? 'a_venir',
        ];
        $evenements = $evenementModel->getAll($filters);

        $pageTitle = 'Événements';
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/evenements/liste.php';
        require BASE_PATH . '/views/layout/footer.php';
    }

    public function detail($id) {
        $evenementModel = new Evenement();
        $evenement = $evenementModel->findById($id);

        if (!$evenement) {
            http_response_code(404);
            require BASE_PATH . '/views/errors/404.php';
            return;
        }

        $pageTitle = $evenement['titre'];
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/evenements/detail.php';
        require BASE_PATH . '/views/layout/footer.php';
    }
}
