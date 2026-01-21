<?php

namespace App\Models;

class Reward
{
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
    }
    public function getDb()
    {
        return $this->db;
    }

    public function redeemVoucher($userId, $pointsCost)
    {

        $stmt = $this->db->prepare("SELECT total_points FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $currentPoints = $stmt->fetchColumn();

        if ($currentPoints < $pointsCost) return false;

        $code = "SAVE5-" . strtoupper(bin2hex(random_bytes(2)));
        $stmt = $this->db->prepare("INSERT INTO vouchers (user_id, code, discount_amount) VALUES (?, ?, 5.00)");
        return $stmt->execute([$userId, $code]);
    }

    public function validateVoucher($code, $userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM vouchers WHERE code = ? AND user_id = ? AND is_used = 0");
        $stmt->execute([$code, $userId]);
        return $stmt->fetch();
    }

    public function markAsUsed($code)
    {
        $stmt = $this->db->prepare("UPDATE vouchers SET is_used = 1 WHERE code = ?");
        return $stmt->execute([$code]);
    }
}
