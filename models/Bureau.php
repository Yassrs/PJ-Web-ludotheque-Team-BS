<?php
class Bureau {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query(
            "SELECT b.*, u.nom, u.prenom, u.email, u.role 
             FROM bureau b 
             JOIN utilisateur u ON b.id_utilisateur = u.id 
             WHERE b.actif = 1 
             ORDER BY FIELD(b.role_bureau, 'president', 'vice_president', 'tresorier', 'secretaire', 'responsable_jeux', 'responsable_comm')"
        );
        return $stmt->fetchAll();
    }

    public function findByUserId($userId) {
        $stmt = $this->db->prepare(
            "SELECT * FROM bureau WHERE id_utilisateur = ? AND actif = 1"
        );
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO bureau (id_utilisateur, role_bureau, date_debut_mandat, date_fin_mandat, actif) 
             VALUES (?, ?, ?, ?, 1)"
        );
        return $stmt->execute([
            $data['id_utilisateur'],
            $data['role_bureau'],
            $data['date_debut_mandat'],
            $data['date_fin_mandat']
        ]);
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
            "UPDATE bureau SET " . implode(', ', $fields) . " WHERE id = ?"
        );
        return $stmt->execute($values);
    }

    public function deactivate($id) {
        $stmt = $this->db->prepare("UPDATE bureau SET actif = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM bureau WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getEndDate() {
        $stmt = $this->db->query(
            "SELECT date_fin_mandat FROM bureau WHERE actif = 1 LIMIT 1"
        );
        $result = $stmt->fetch();
        return $result ? $result['date_fin_mandat'] : null;
    }
}
