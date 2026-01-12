<?php
// require_once __DIR__ . '/../models/Purchase.php';
// require_once __DIR__ . '/../models/User.php';

namespace App\Controllers;

use App\Models\Purchase;
use App\Models\User;

class ShopController
{
    private $purchaseModel;
    private $userModel;

    public function __construct($db)
    {
        $this->purchaseModel = new Purchase($db);
        $this->userModel = new User($db);
    }

    // Méthode existante - à analyser et compléter
    public function processPurchase($userId, $cartItems)
    {
        $totalAmount = 0;

        // Calcul du montant total
        foreach ($cartItems as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        // Enregistrement de l'achat (existant)
        $purchaseId = $this->purchaseModel->create([
            'user_id' => $userId,
            'total_amount' => $totalAmount,
            'status' => 'completed'
        ]);

        // TODO: Ajouter la logique pour attribuer les points de fidélité
        // Calculer les points gagnés (10 points pour chaque 100$)
        // Enregistrer la transaction de points
        // Mettre à jour le solde de l'utilisateur

        // Retourner le résultat
        return [
            'success' => true,
            'purchase_id' => $purchaseId,
            'total_amount' => $totalAmount,
            'points_earned' => 0 // À calculer
        ];
    }

    // À compléter par les apprenants
    private function calculatePoints($amount)
    {
        // TODO: Implémenter la logique de calcul
        // 10 points pour chaque 100$ dépensés
        return 0;
    }
}
