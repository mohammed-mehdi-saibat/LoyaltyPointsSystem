<?php

namespace App\Controllers;

use App\Models\Admin;
use App\Models\User;

class AdminController
{
    private $adminModel;
    private $twig;

    public function __construct($db, $twig)
    {
        $this->adminModel = new Admin($db);
        $this->twig = $twig;
    }

    public function index()
    {

        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /FacileAchat/public/login');
            exit;
        }

        $users = $this->adminModel->getAllUsers();
        $vouchers = $this->adminModel->getAllVouchers();

        echo $this->twig->render('admin/dashboard.twig', [
            'users' => $users,
            'vouchers' => $vouchers
        ]);
    }

    public function deleteUser()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /FacileAchat/public/login');
            exit;
        }

        $userIdToDelete = $_POST['user_id'] ?? null;

        if ($userIdToDelete && $userIdToDelete != $_SESSION['user_id']) {
            if ($this->adminModel->deleteUser($userIdToDelete)) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => "Utilisateur supprimé avec succès."];
            }
        }

        header('Location: /FacileAchat/public/admin');
        exit;
    }
}
