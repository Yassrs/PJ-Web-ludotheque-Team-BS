<?php
class DemandeController {

    public function emprunt($jeuId) {
        requireLogin();
        if (!isMembre()) {
            setFlash('error', 'Seuls les membres peuvent emprunter des jeux.');
            redirect('/ludotheque/jeu/' . $jeuId);
        }
        $this->creerDemande($jeuId, 'emprunt');
    }

    public function location($jeuId) {
        requireLogin();
        // Seuls les non-membres peuvent louer — les membres doivent emprunter
        if (isMembre()) {
            setFlash('error', 'En tant que membre, vous bénéficiez de l\'emprunt gratuit. La location est réservée aux non-membres.');
            redirect('/ludotheque/jeu/' . $jeuId);
        }
        $this->creerDemande($jeuId, 'location');
    }

    public function reservation($jeuId) {
        requireLogin();
        
        $dateReservation = sanitize($_POST['date_reservation'] ?? '');
        
        if (empty($dateReservation)) {
            setFlash('error', 'Veuillez sélectionner une date de réservation.');
            redirect('/ludotheque/jeu/' . $jeuId);
        }

        // 1. Vérifier que c'est un jeudi
        $jour = date('N', strtotime($dateReservation));
        if ($jour != 4) { // 4 = jeudi (ISO-8601)
            setFlash('error', 'Les réservations sont uniquement possibles pour un jeudi.');
            redirect('/ludotheque/jeu/' . $jeuId);
        }
        
        // 2. Vérifier que la date est dans le futur
        if (strtotime($dateReservation) < strtotime('today')) {
            setFlash('error', 'La date de réservation doit être dans le futur.');
            redirect('/ludotheque/jeu/' . $jeuId);
        }

        // 3. Vérifier que la date n'est PAS en période de vacances universitaires
        $vacancesModel = new VacancesUniversitaires();
        $vacances = $vacancesModel->isInVacances($dateReservation);
        if ($vacances) {
            $libelle = htmlspecialchars($vacances['libelle']);
            $debut = date('d/m/Y', strtotime($vacances['date_debut']));
            $fin = date('d/m/Y', strtotime($vacances['date_fin']));
            setFlash('error', "Réservation impossible : le " . date('d/m/Y', strtotime($dateReservation)) 
                . " tombe pendant les {$libelle} ({$debut} — {$fin}). Veuillez choisir un jeudi hors vacances.");
            redirect('/ludotheque/jeu/' . $jeuId);
        }

        // 4. Vérifier que le nombre max de jeux pour ce jeudi n'est pas atteint
        $demandeModel = new Demande();
        $nbReservations = $demandeModel->countReservationsForDate($dateReservation);
        if ($nbReservations >= MAX_JEUX_PAR_JEUDI) {
            setFlash('error', 'Le nombre maximum de jeux réservables pour ce jeudi est atteint (' 
                . MAX_JEUX_PAR_JEUDI . ' jeux). Veuillez choisir un autre jeudi.');
            redirect('/ludotheque/jeu/' . $jeuId);
        }

        // 5. Vérifier que le jeu n'est pas déjà réservé pour cette date
        if ($demandeModel->isReservedForDate($jeuId, $dateReservation)) {
            setFlash('error', 'Ce jeu est déjà réservé pour cette date.');
            redirect('/ludotheque/jeu/' . $jeuId);
        }

        $this->creerDemande($jeuId, 'reservation', $dateReservation, $dateReservation);
    }

    private function creerDemande($jeuId, $type, $dateDebut = null, $dateFin = null) {
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token de sécurité invalide.');
            redirect('/ludotheque/jeu/' . $jeuId);
        }

        $jeuModel = new Jeu();
        $jeu = $jeuModel->findById($jeuId);

        if (!$jeu) {
            setFlash('error', 'Jeu introuvable.');
            redirect('/ludotheque');
        }

        if ($jeu['statut'] !== STATUT_EN_STOCK && $type !== 'reservation') {
            setFlash('error', 'Ce jeu n\'est pas disponible actuellement.');
            redirect('/ludotheque/jeu/' . $jeuId);
        }

        // Vérifier les demandes actives
        $demandeModel = new Demande();
        if ($demandeModel->hasActiveRequest(currentUserId(), $jeuId)) {
            setFlash('warning', 'Vous avez déjà une demande en cours pour ce jeu.');
            redirect('/ludotheque/jeu/' . $jeuId);
        }

        // Dates par défaut pour emprunt/location
        if (!$dateDebut) {
            $duree = intval($_POST['duree'] ?? 7);
            $duree = max(DUREE_MIN_JOURS, min(DUREE_MAX_JOURS, $duree));
            $dateDebut = date('Y-m-d');
            $dateFin = date('Y-m-d', strtotime("+{$duree} days"));
        }

        $data = [
            'id_utilisateur' => currentUserId(),
            'id_jeu' => $jeuId,
            'type_demande' => $type,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
        ];

        $id = $demandeModel->create($data);
        if ($id) {
            $typeLabel = ['emprunt' => 'emprunt', 'location' => 'location', 'reservation' => 'réservation'];
            setFlash('success', 'Votre demande de ' . ($typeLabel[$type] ?? $type) . ' a été enregistrée. Elle sera traitée par un administrateur.');
        } else {
            setFlash('error', 'Erreur lors de la création de la demande.');
        }

        redirect('/mon-compte/demandes');
    }
}
