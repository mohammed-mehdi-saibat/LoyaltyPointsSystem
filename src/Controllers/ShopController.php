<?php

namespace App\Controllers;

use App\Models\Purchase;
use App\Models\PointsTransaction;
use App\Models\Reward;

class ShopController
{
    private $purchaseModel;
    private $pointsModel;
    private $twig;
    private $db;

    public function __construct($db, $twig)
    {
        $this->db = $db;
        $this->purchaseModel = new Purchase($db);
        $this->pointsModel = new PointsTransaction($db);
        $this->twig = $twig;
    }

    public function showShop()
    {
        echo $this->twig->render('shop.twig');
    }

    public function buy()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /FacileAchat/public/login');
            exit;
        }

        $price = isset($_POST['price']) ? (float)$_POST['price'] : 0;
        $voucherCode = isset($_POST['voucher_code']) ? trim($_POST['voucher_code']) : null;
        $discount = 0;
        $messagePrefix = "";

        // Check if voucher is valid
        if (!empty($voucherCode)) {
            $rewardModel = new Reward($this->db);
            $voucher = $rewardModel->validateVoucher($voucherCode, $_SESSION['user_id']);

            if ($voucher) {
                $discount = 5.00;
                $rewardModel->markAsUsed($voucherCode);
                $messagePrefix = "Code promo appliqué ! (-5$) ";
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => "Code invalide ou déjà utilisé."];
                header('Location: /FacileAchat/public/');
                exit;
            }
        }

        $finalPrice = max(0, $price - $discount);
        $cartItems = [['price' => $finalPrice, 'quantity' => 1]];
        $result = $this->processPurchase($_SESSION['user_id'], $cartItems);

        if ($result['success']) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => $messagePrefix . "Achat réussi pour " . $finalPrice . "$ !"
            ];
            header('Location: /FacileAchat/public/dashboard');
            exit;
        }
    }

    public function processPurchase($userId, $cartItems)
    {
        $totalAmount = array_reduce($cartItems, function ($sum, $item) {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);

        $purchaseId = $this->purchaseModel->create([
            'user_id' => $userId,
            'total_amount' => $totalAmount,
            'status' => 'completed'
        ]);

        $pointsEarned = $this->pointsModel->calculatePoints($totalAmount);

        if ($pointsEarned > 0) {
            $this->pointsModel->addTransaction(
                $userId,
                $pointsEarned,
                'earned',
                "Points gagnés pour l'achat #$purchaseId"
            );
        }

        return [
            'success' => true,
            'points_earned' => $pointsEarned,
            'total_amount' => $totalAmount
        ];
    }
}
