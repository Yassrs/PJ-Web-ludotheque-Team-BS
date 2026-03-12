<?php
class CompteController {

    public function profil() {
        requireLogin();
        $user = currentUser();
        
        $demandeModel = new Demande();
        $allDemandes = $demandeModel->getByUser(currentUserId());
        $recentDemandes = array_slice($allDemandes, 0, 5);

        // Statistiques pour le tableau de bord personnel
        $stats = [
            'en_attente' => 0,
            'validee' => 0,
            'refusee' => 0,
            'total' => count($allDemandes),
        ];
        foreach ($allDemandes as $d) {
            if (isset($stats[$d['statut']])) $stats[$d['statut']]++;
        }

        // Emprunts/locations actifs (validés, non expirés)
        $empruntsActifs = array_filter($allDemandes, function($d) {
            return $d['statut'] === DEMANDE_VALIDEE 
                && in_array($d['type_demande'], ['emprunt', 'location'])
                && strtotime($d['date_fin']) >= strtotime('today');
        });

        // Réservations à venir (validées ou en attente)
        $reservations = array_filter($allDemandes, function($d) {
            return $d['type_demande'] === 'reservation'
                && in_array($d['statut'], [DEMANDE_EN_ATTENTE, DEMANDE_VALIDEE])
                && strtotime($d['date_debut']) >= strtotime('today');
        });

        $pageTitle = 'Mon Compte';
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/compte/profil.php';
        require BASE_PATH . '/views/layout/footer.php';
    }

    public function demandes() {
        requireLogin();
        
        $demandeModel = new Demande();
        $demandes = $demandeModel->getByUser(currentUserId());

        $pageTitle = 'Mes Demandes';
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/compte/demandes.php';
        require BASE_PATH . '/views/layout/footer.php';
    }

    public function emprunts() {
        requireLogin();
        
        $demandeModel = new Demande();
        $allDemandes = $demandeModel->getByUser(currentUserId());

        // Emprunts/locations validés
        $emprunts = array_filter($allDemandes, function($d) {
            return $d['statut'] === DEMANDE_VALIDEE && in_array($d['type_demande'], ['emprunt', 'location']);
        });

        // Réservations validées ou en attente à venir
        $reservations = array_filter($allDemandes, function($d) {
            return $d['type_demande'] === 'reservation'
                && in_array($d['statut'], [DEMANDE_EN_ATTENTE, DEMANDE_VALIDEE]);
        });

        $pageTitle = isMembre() ? 'Mes Emprunts' : 'Mes Locations';
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/compte/emprunts.php';
        require BASE_PATH . '/views/layout/footer.php';
    }
}
