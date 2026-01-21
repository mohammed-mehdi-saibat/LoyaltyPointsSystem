<?php

namespace App\Models;

use PDO;

class Admin
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllUsers()
    {

        $stmt = $this->db->query("SELECT id, email, total_points, role FROM users ORDER BY total_points DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllVouchers()
    {
        $stmt = $this->db->query("
            SELECT v.*, u.email 
            FROM vouchers v 
            JOIN users u ON v.user_id = u.id 
            ORDER BY v.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteUser($userId)
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("DELETE FROM points_transactions WHERE user_id = ?");
            $stmt->execute([$userId]);

            $stmt = $this->db->prepare("DELETE FROM vouchers WHERE user_id = ?");
            $stmt->execute([$userId]);

            $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$userId]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
