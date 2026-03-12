<?php
/**
 * Modèle VacancesUniversitaires
 * Gère la vérification des périodes de vacances universitaires.
 */
class VacancesUniversitaires {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Vérifie si une date tombe pendant les vacances universitaires.
     * @param string $date Format Y-m-d
     * @return array|false Retourne la période de vacances si trouvée, false sinon
     */
    public function isInVacances($date) {
        $stmt = $this->db->prepare(
            "SELECT * FROM vacances_universitaires 
             WHERE ? BETWEEN date_debut AND date_fin 
             LIMIT 1"
        );
        $stmt->execute([$date]);
        return $stmt->fetch();
    }

    /**
     * Récupère toutes les périodes de vacances pour une année scolaire.
     * @param string $anneeScolaire ex: '2025-2026'
     * @return array
     */
    public function getByAnneeScolaire($anneeScolaire = '2025-2026') {
        $stmt = $this->db->prepare(
            "SELECT * FROM vacances_universitaires 
             WHERE annee_scolaire = ? 
             ORDER BY date_debut ASC"
        );
        $stmt->execute([$anneeScolaire]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère les prochaines vacances à venir.
     * @return array|false
     */
    public function getProchaines() {
        $stmt = $this->db->prepare(
            "SELECT * FROM vacances_universitaires 
             WHERE date_fin >= CURDATE() 
             ORDER BY date_debut ASC 
             LIMIT 1"
        );
        $stmt->execute();
        return $stmt->fetch();
    }
}
