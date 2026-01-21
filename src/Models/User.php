<?php

namespace App\Models;

use PDO;

class User
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function register($name, $email, $password)
    {

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':name'     => $name,
            ':email'    => $email,
            ':password' => $hashedPassword
        ]);
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function login($email, $password)
    {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
    }

    public function getUserById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
