<?php
// Inclure l'autoloader de Predis
require 'vendor/autoload.php';

// Configuration du serveur Redis
$redisConfig = [
    'scheme' => 'tcp',
    'host' => 'redis-12156.c1.asia-northeast1-1.gce.cloud.redislabs.com',
    'port' => 12156,
    'password' => 'A2giNtgc8yPBm5bTwcwkxteau2PVfO4Z',
];

try {
    // Créer un nouveau client Predis
    $redis = new Predis\Client($redisConfig);

    // Opérations CRUD

    // Créer un nouvel utilisateur
    function createUser($userId, $userName, $userEmail) {
        global $redis;

        $userKey = "user:$userId";

        $userData = [
            'id' => $userId,
            'name' => $userName,
            'email' => $userEmail,
        ];

        $redis->hMset($userKey, $userData);
    }

    // Lire les données utilisateur
    function readUser($userId) {
        global $redis;

        $userKey = "user:$userId";

        return $redis->hGetAll($userKey);
    }

    // Mettre à jour les données utilisateur
    function updateUser($userId, $newName, $newEmail) {
        global $redis;

        $userKey = "user:$userId";

        $userData = [
            'name' => $newName,
            'email' => $newEmail,
        ];

        $redis->hMset($userKey, $userData);
    }

    // Supprimer un utilisateur
    function deleteUser($userId) {
        global $redis;

        $userKey = "user:$userId";

        $redis->del($userKey);
    }

    // Exemple d'utilisation

    // Créer un nouvel utilisateur
    createUser(1, 'John Doe', 'john@example.com');

    // Lire les données utilisateur
    $getUserData = readUser(1);
    echo "Données de l'utilisateur : " . print_r($getUserData, true) . "\n";

    // Mettre à jour les données utilisateur
    updateUser(1, 'John Doe Jr.', 'john.jr@example.com');

    // Lire les données utilisateur mises à jour
    $updatedUserData = readUser(1);
    echo "Données utilisateur mises à jour : " . print_r($updatedUserData, true) . "\n";

    // Supprimer l'utilisateur
    deleteUser(1);

    // Tenter de lire les données d'un utilisateur supprimé
    $deletedUserData = readUser(1);
    echo "Données utilisateur supprimées : " . print_r($deletedUserData, true) . "\n";

    // Fermer la connexion Redis
    $redis->disconnect();
} catch (Predis\Response\ServerException $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}
?>
