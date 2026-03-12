<?php
class MessageContact {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO message_contact (nom, email, sujet, message) VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([
            $data['nom'], $data['email'], $data['sujet'], $data['message']
        ]);
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM message_contact ORDER BY date_envoi DESC")->fetchAll();
    }

    public function markAsRead($id) {
        $stmt = $this->db->prepare("UPDATE message_contact SET lu = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
