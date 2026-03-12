<?php
class Jeu {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM jeu WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAll($filters = []) {
        $sql = "SELECT * FROM jeu WHERE 1=1";
        $params = [];

        // Filtre par nombre de joueurs
        if (!empty($filters['nb_joueurs'])) {
            $sql .= " AND nb_joueurs_min <= ? AND nb_joueurs_max >= ?";
            $params[] = $filters['nb_joueurs'];
            $params[] = $filters['nb_joueurs'];
        }

        // Filtre par difficulté d'apprentissage
        if (!empty($filters['difficulte_apprentissage'])) {
            $sql .= " AND difficulte_apprentissage = ?";
            $params[] = $filters['difficulte_apprentissage'];
        }

        // Filtre par difficulté de jeu
        if (!empty($filters['difficulte_jeu'])) {
            $sql .= " AND difficulte_jeu = ?";
            $params[] = $filters['difficulte_jeu'];
        }

        // Filtre par temps de jeu
        if (!empty($filters['temps_max'])) {
            $sql .= " AND temps_jeu_minutes <= ?";
            $params[] = $filters['temps_max'];
        }
        if (!empty($filters['temps_min'])) {
            $sql .= " AND temps_jeu_minutes >= ?";
            $params[] = $filters['temps_min'];
        }

        // Filtre par statut
        if (!empty($filters['statut'])) {
            $sql .= " AND statut = ?";
            $params[] = $filters['statut'];
        }

        // Recherche par nom
        if (!empty($filters['recherche'])) {
            $sql .= " AND nom LIKE ?";
            $params[] = '%' . $filters['recherche'] . '%';
        }

        // Tri
        $tri = $filters['tri'] ?? 'nom';
        $ordre = $filters['ordre'] ?? 'ASC';
        $allowedTri = ['nom', 'temps_jeu_minutes', 'nb_joueurs_min', 'date_ajout'];
        $allowedOrdre = ['ASC', 'DESC'];
        
        if (in_array($tri, $allowedTri) && in_array(strtoupper($ordre), $allowedOrdre)) {
            $sql .= " ORDER BY {$tri} {$ordre}";
        } else {
            $sql .= " ORDER BY nom ASC";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO jeu (nom, description, image, nb_joueurs_min, nb_joueurs_max, 
             difficulte_apprentissage, difficulte_jeu, temps_jeu_minutes, regles, statut) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['nom'],
            $data['description'] ?? '',
            $data['image'] ?? 'default_jeu.png',
            $data['nb_joueurs_min'],
            $data['nb_joueurs_max'],
            $data['difficulte_apprentissage'],
            $data['difficulte_jeu'],
            $data['temps_jeu_minutes'],
            $data['regles'] ?? '',
            $data['statut'] ?? STATUT_EN_STOCK
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        $values = [];
        foreach ($data as $key => $value) {
            $fields[] = "{$key} = ?";
            $values[] = $value;
        }
        $values[] = $id;
        $stmt = $this->db->prepare(
            "UPDATE jeu SET " . implode(', ', $fields) . " WHERE id = ?"
        );
        return $stmt->execute($values);
    }

    public function updateStatut($id, $statut) {
        $stmt = $this->db->prepare("UPDATE jeu SET statut = ? WHERE id = ?");
        return $stmt->execute([$statut, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM jeu WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function countByStatut() {
        $stmt = $this->db->query(
            "SELECT statut, COUNT(*) as total FROM jeu GROUP BY statut"
        );
        $results = $stmt->fetchAll();
        $counts = ['en_stock' => 0, 'emprunte' => 0, 'loue' => 0, 'perdu' => 0, 'total' => 0];
        foreach ($results as $row) {
            $counts[$row['statut']] = (int)$row['total'];
            $counts['total'] += (int)$row['total'];
        }
        return $counts;
    }

    /**
     * Compte le nombre total de jeux dans la ludothèque.
     * @return int
     */
    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM jeu");
        return (int) $stmt->fetchColumn();
    }
}
