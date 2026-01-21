<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\PointsTransaction;

class AuthController
{
    private $userModel;
    private $db;
    private $twig;

    public function __construct($db, $twig)
    {
        $this->db = $db;
        $this->twig = $twig;
        $this->userModel = new User($db);
    }

    public function showRegister()
    {
        echo $this->twig->render('auth/register.twig');


        unset($_SESSION['flash']);
    }

    public function handleRegister()
    {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($this->userModel->register($name, $email, $password)) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => "Inscription réussie ! Vous pouvez maintenant vous connecter."
            ];
            header('Location: /FacileAchat/public/login');
            exit;
        } else {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => "Erreur lors de l'inscription. L'email est peut-être déjà utilisé."
            ];
            $this->showRegister();
        }
    }

    public function showLogin($error = null)
    {
        echo $this->twig->render('auth/login.twig', ['error' => $error]);


        unset($_SESSION['flash']);
    }

    public function handleLogin()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->login($email, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => "Bon retour, " . $user['name'] . " !"
            ];

            header('Location: /FacileAchat/public/dashboard');
            exit;
        } else {
            $this->showLogin("Identifiants incorrects.");
        }
    }

    public function showDashboard()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /FacileAchat/public/login');
            exit;
        }

        $userId = $_SESSION['user_id'];

        $queryUser = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $queryUser->execute([$userId]);
        $user = $queryUser->fetch(\PDO::FETCH_ASSOC);

        $queryTrans = $this->db->prepare("SELECT * FROM points_transactions WHERE user_id = ? ORDER BY created_at DESC");
        $queryTrans->execute([$userId]);
        $transactions = $queryTrans->fetchAll(\PDO::FETCH_ASSOC);

        $queryVouchers = $this->db->prepare("SELECT * FROM vouchers WHERE user_id = ? AND is_used = 0");
        $queryVouchers->execute([$userId]);
        $vouchers = $queryVouchers->fetchAll(\PDO::FETCH_ASSOC);
        // var_dump($transactions);
        // exit;
        echo $this->twig->render('dashboard.twig', [
            'user'         => $user,
            'transactions' => $transactions,
            'vouchers'     => $vouchers
        ]);
    }

    public function logout()
    {
        session_destroy();

        session_start();
        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => "Vous avez été déconnecté avec succès."
        ];
        header('Location: /FacileAchat/public/login');
        exit;
    }
}
