<?php
class Utilisateur {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM utilisateur WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, role) 
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['nom'],
            $data['prenom'],
            $data['email'],
            password_hash($data['mot_de_passe'], PASSWORD_DEFAULT),
            $data['role'] ?? ROLE_NON_MEMBRE
        ]);
        return $this->db->lastInsertId();
    }

    public function authenticate($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            return $user;
        }
        return false;
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
            "UPDATE utilisateur SET " . implode(', ', $fields) . " WHERE id = ?"
        );
        return $stmt->execute($values);
    }

    public function updateRole($id, $role) {
        $stmt = $this->db->prepare("UPDATE utilisateur SET role = ? WHERE id = ?");
        return $stmt->execute([$role, $id]);
    }

    public function getAll($role = null) {
        if ($role) {
            $stmt = $this->db->prepare("SELECT * FROM utilisateur WHERE role = ? ORDER BY nom, prenom");
            $stmt->execute([$role]);
        } else {
            $stmt = $this->db->query("SELECT * FROM utilisateur ORDER BY nom, prenom");
        }
        return $stmt->fetchAll();
    }

    public function getAllNonAdmin() {
        $stmt = $this->db->prepare(
            "SELECT * FROM utilisateur WHERE role NOT IN (?, ?) ORDER BY nom, prenom"
        );
        $stmt->execute([ROLE_ADMIN, ROLE_PRESIDENT]);
        return $stmt->fetchAll();
    }

    public function getAdmins() {
        $stmt = $this->db->prepare(
            "SELECT * FROM utilisateur WHERE role IN (?, ?) ORDER BY role DESC, nom"
        );
        $stmt->execute([ROLE_ADMIN, ROLE_PRESIDENT]);
        return $stmt->fetchAll();
    }

    public function count() {
        return $this->db->query("SELECT COUNT(*) FROM utilisateur")->fetchColumn();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM utilisateur WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function emailExists($email, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = ? AND id != ?");
            $stmt->execute([$email, $excludeId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = ?");
            $stmt->execute([$email]);
        }
        return $stmt->fetchColumn() > 0;
    }
}
