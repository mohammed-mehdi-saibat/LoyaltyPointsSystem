<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Loader\FilesystemLoader;
use Twig\Environment;


$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader, [
    'cache' => false,
    'debug' => true
]);


$requestUri = $_SERVER['REQUEST_URI'];
$basePath = '/FacileAchat/public';
$path = str_replace($basePath, '', $requestUri);
$path = strtok($path, '?');


if ($path === '/' || $path === '') {

    echo $twig->render('home.twig', [
        'title' => 'FacileAchat - Accueil',
        'welcome' => 'Bienvenue sur votre plateforme de fidélité !'
    ]);
} else {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 - Page Non Trouvée</h1>";
}
