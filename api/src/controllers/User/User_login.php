<?php

include_once '../../config/config.php';
include_once '../../models/User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);

    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->Email) && !empty($data->Password)) {
        $user->Email = $data->Email;
        $user->Password = $data->Password; // Pas besoin de hasher pour la vérification

        if ($user->login()) {
            http_response_code(200); // OK
            echo json_encode(array("message" => "Connexion réussie.", "UserID" => $user->UserID));
        } else {
            http_response_code(401); // Unauthorized
            echo json_encode(array("message" => "Email ou mot de passe incorrect."));
        }
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(array("message" => "Email et mot de passe requis."));
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>
