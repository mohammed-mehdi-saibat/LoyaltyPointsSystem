<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Core\Database;
use App\Controllers\AuthController;
use App\Controllers\ShopController;
use App\Controllers\RewardController;

$db = Database::getConnection();
$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader);

$twig->addGlobal('session', $_SESSION);

$authController = new AuthController($db, $twig);
$shopController = new ShopController($db, $twig);
$rewardController = new RewardController($db, $twig);

$url = $_SERVER['REQUEST_URI'];
$basePath = '/FacileAchat/public';
$route = str_replace($basePath, '', $url);
$route = explode('?', $route)[0];

switch ($route) {
    case '/':
    case '':
        $shopController->showShop();
        break;
    case '/register':
        $_SERVER['REQUEST_METHOD'] === 'POST' ? $authController->handleRegister() : $authController->showRegister();
        break;
    case '/login':
        $_SERVER['REQUEST_METHOD'] === 'POST' ? $authController->handleLogin() : $authController->showLogin();
        break;
    case '/dashboard':
        $authController->showDashboard();
        break;
    case '/logout':
        $authController->logout();
        break;
    case '/buy':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') $shopController->buy();
        break;
    case '/rewards':
        $rewardController->showRewards();
        break;
    case '/redeem':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') $rewardController->redeem();
        break;
    case '/admin':
        $controller = new App\Controllers\AdminController($db, $twig);
        $controller->index();
        break;
    case '/admin/delete-user':
        $controller = new App\Controllers\AdminController($db, $twig);
        $controller->deleteUser();
        break;
    default:
        http_response_code(404);
        echo "404 - Page non trouvée";
        break;
}
