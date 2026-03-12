<?php
class Demande {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById($id) {
        $stmt = $this->db->prepare(
            "SELECT d.*, j.nom as jeu_nom, j.image as jeu_image, 
                    u.nom as user_nom, u.prenom as user_prenom, u.email as user_email, u.role as user_role,
                    a.nom as admin_nom, a.prenom as admin_prenom
             FROM demande d
             JOIN jeu j ON d.id_jeu = j.id
             JOIN utilisateur u ON d.id_utilisateur = u.id
             LEFT JOIN utilisateur a ON d.id_admin = a.id
             WHERE d.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByUser($userId) {
        $stmt = $this->db->prepare(
            "SELECT d.*, j.nom as jeu_nom, j.image as jeu_image
             FROM demande d
             JOIN jeu j ON d.id_jeu = j.id
             WHERE d.id_utilisateur = ?
             ORDER BY d.date_demande DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getAll($filters = []) {
        $sql = "SELECT d.*, j.nom as jeu_nom, j.image as jeu_image,
                       u.nom as user_nom, u.prenom as user_prenom, u.email as user_email, u.role as user_role
                FROM demande d
                JOIN jeu j ON d.id_jeu = j.id
                JOIN utilisateur u ON d.id_utilisateur = u.id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['statut'])) {
            $sql .= " AND d.statut = ?";
            $params[] = $filters['statut'];
        }
        if (!empty($filters['type_demande'])) {
            $sql .= " AND d.type_demande = ?";
            $params[] = $filters['type_demande'];
        }

        $sql .= " ORDER BY FIELD(d.statut, 'en_attente', 'validee', 'refusee'), d.date_demande DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getPending() {
        return $this->getAll(['statut' => DEMANDE_EN_ATTENTE]);
    }

    public function create($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO demande (id_utilisateur, id_jeu, type_demande, statut, date_debut, date_fin) 
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['id_utilisateur'],
            $data['id_jeu'],
            $data['type_demande'],
            DEMANDE_EN_ATTENTE,
            $data['date_debut'],
            $data['date_fin']
        ]);
        return $this->db->lastInsertId();
    }

    public function accept($id, $adminId) {
        $this->db->beginTransaction();
        try {
            // Mettre à jour la demande
            $stmt = $this->db->prepare(
                "UPDATE demande SET statut = ?, date_traitement = NOW(), id_admin = ? WHERE id = ?"
            );
            $stmt->execute([DEMANDE_VALIDEE, $adminId, $id]);

            // Récupérer la demande pour mettre à jour le jeu
            $demande = $this->findById($id);
            if ($demande) {
                $newStatut = ($demande['type_demande'] === 'emprunt') ? STATUT_EMPRUNTE : STATUT_LOUE;
                if ($demande['type_demande'] !== 'reservation') {
                    $jeuModel = new Jeu();
                    $jeuModel->updateStatut($demande['id_jeu'], $newStatut);
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function reject($id, $adminId, $motif = null) {
        $stmt = $this->db->prepare(
            "UPDATE demande SET statut = ?, date_traitement = NOW(), id_admin = ?, motif_refus = ? WHERE id = ?"
        );
        return $stmt->execute([DEMANDE_REFUSEE, $adminId, $motif, $id]);
    }

    public function countPending() {
        $stmt = $this->db->query(
            "SELECT COUNT(*) FROM demande WHERE statut = 'en_attente'"
        );
        return $stmt->fetchColumn();
    }

    public function hasActiveRequest($userId, $jeuId) {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM demande 
             WHERE id_utilisateur = ? AND id_jeu = ? AND statut IN ('en_attente', 'validee')"
        );
        $stmt->execute([$userId, $jeuId]);
        return $stmt->fetchColumn() > 0;
    }

    public function isReservedForDate($jeuId, $date) {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM demande 
             WHERE id_jeu = ? AND type_demande = 'reservation' 
             AND date_debut = ? AND statut IN ('en_attente', 'validee')"
        );
        $stmt->execute([$jeuId, $date]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Compte le nombre total de jeux réservés pour une date donnée (tous utilisateurs).
     * @param string $date Format Y-m-d
     * @return int
     */
    public function countReservationsForDate($date) {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM demande 
             WHERE type_demande = 'reservation' 
             AND date_debut = ? AND statut IN ('en_attente', 'validee')"
        );
        $stmt->execute([$date]);
        return (int) $stmt->fetchColumn();
    }
}
