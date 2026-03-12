<?php
class Evenement {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById($id) {
        $stmt = $this->db->prepare(
            "SELECT e.*, u.nom as createur_nom, u.prenom as createur_prenom 
             FROM evenement e 
             LEFT JOIN utilisateur u ON e.id_createur = u.id 
             WHERE e.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAll($filters = []) {
        $sql = "SELECT e.*, u.nom as createur_nom, u.prenom as createur_prenom 
                FROM evenement e 
                LEFT JOIN utilisateur u ON e.id_createur = u.id 
                WHERE 1=1";
        $params = [];

        if (!empty($filters['categorie'])) {
            $sql .= " AND e.categorie = ?";
            $params[] = $filters['categorie'];
        }

        if (!empty($filters['periode'])) {
            if ($filters['periode'] === 'a_venir') {
                $sql .= " AND e.date_evenement >= CURDATE()";
            } elseif ($filters['periode'] === 'passes') {
                $sql .= " AND e.date_evenement < CURDATE()";
            }
        }

        $sql .= " ORDER BY e.date_evenement " . (($filters['periode'] ?? '') === 'passes' ? 'DESC' : 'ASC');

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getUpcoming($limit = 4) {
        $stmt = $this->db->prepare(
            "SELECT * FROM evenement WHERE date_evenement >= CURDATE() 
             ORDER BY date_evenement ASC LIMIT ?"
        );
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function getByCategorie($categorie, $limit = 1) {
        $stmt = $this->db->prepare(
            "SELECT * FROM evenement 
             WHERE categorie = ? AND date_evenement >= CURDATE() 
             ORDER BY date_evenement ASC LIMIT ?"
        );
        $stmt->execute([$categorie, $limit]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO evenement (titre, description, date_evenement, heure, lieu, image, categorie, id_createur) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['titre'],
            $data['description'] ?? '',
            $data['date_evenement'],
            $data['heure'],
            $data['lieu'],
            $data['image'] ?? 'default_event.png',
            $data['categorie'],
            $data['id_createur'] ?? null
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
            "UPDATE evenement SET " . implode(', ', $fields) . " WHERE id = ?"
        );
        return $stmt->execute($values);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM evenement WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function countUpcoming() {
        $stmt = $this->db->query(
            "SELECT COUNT(*) FROM evenement WHERE date_evenement >= CURDATE()"
        );
        return $stmt->fetchColumn();
    }
}
