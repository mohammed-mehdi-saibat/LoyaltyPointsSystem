<?php

namespace App\Models;

use PDO;

class PointsTransaction
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function calculatePoints(float $amount): int
    {
        return floor($amount / 100) * 10;
    }

    public function addTransaction(int $userId, int $amount, string $type, string $description)
    {
        $stmt = $this->db->prepare("SELECT total_points FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $currentBalance = (int)$stmt->fetchColumn();


        $newBalance = ($type === 'earned') ? $currentBalance + $amount : $currentBalance - $amount;

        if ($newBalance < 0) {
            $newBalance = 0;
        }



        $stmt = $this->db->prepare("INSERT INTO points_transactions (user_id, `type`, amount, description, balance_after) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $type, $amount, $description, $newBalance]);

        $stmt = $this->db->prepare("UPDATE users SET total_points = ? WHERE id = ?");
        $stmt->execute([$newBalance, $userId]);

        return $newBalance;
    }

    // public function getHistoryByUserId($userId)
    // {
    //     $stmt = $this->db->prepare("SELECT * FROM points_transactions WHERE user_id = ? ORDER BY created_at DESC");
    //     $stmt->execute([$userId]);
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

    public function getHistoryByUserId($userId)
    {
        $stmt = $this->db->prepare("SELECT id, type, amount, description, balance_after, created_at FROM points_transactions WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
