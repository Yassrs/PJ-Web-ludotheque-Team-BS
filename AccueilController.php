<?php
class AccueilController {
    public function index() {
        $evenementModel = new Evenement();
        
        // Récupérer un événement par catégorie pour la page d'accueil
        $salleJeudi = $evenementModel->getByCategorie('salle_jeudi', 3);
        $jeuJeudi = $evenementModel->getByCategorie('jeu_jeudi', 3);
        $soireeJeux = $evenementModel->getByCategorie('soiree_jeux', 3);
        $occasionnel = $evenementModel->getByCategorie('occasionnel', 3);
        
        $pageTitle = 'Accueil';
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/accueil/index.php';
        require BASE_PATH . '/views/layout/footer.php';
    }
}
