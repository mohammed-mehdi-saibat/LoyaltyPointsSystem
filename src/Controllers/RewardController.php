<?php

namespace App\Controllers;

use App\Models\Reward;
use App\Models\PointsTransaction;
use App\Models\User;

class RewardController
{
    private $rewardModel;
    private $pointsModel;
    private $twig;

    public function __construct($db, $twig)
    {
        $this->rewardModel = new Reward($db);
        $this->pointsModel = new PointsTransaction($db);
        $this->twig = $twig;
    }

    public function showRewards($error = null)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /FacileAchat/public/login');
            exit;
        }

        $db = $this->rewardModel->getDb();
        $userModel = new User($db);
        $userData = $userModel->getUserById($_SESSION['user_id']);

        echo $this->twig->render('rewards.twig', [
            'user'  => $userData,
            'error' => $error
        ]);

        unset($_SESSION['flash']);
    }

    public function redeem()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /FacileAchat/public/login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $cost = 500;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->rewardModel->redeemVoucher($userId, $cost)) {
                $this->pointsModel->addTransaction(
                    $userId,
                    $cost,
                    'used',
                    'Conversion de 500 pts en bon de 5€'
                );

                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => "Code généré avec succès !"
                ];

                header('Location: /FacileAchat/public/dashboard');
                exit;
            } else {
                $this->showRewards("Points insuffisants.");
            }
        }
    }
}
