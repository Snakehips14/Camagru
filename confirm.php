<?php

$user_id = $_GET['id'];
$token = $_GET['token'];
require 'db.php';
$req = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$req->execute([$user_id]);
$user = $req->fetch();
session_start();
//var_dump($user->confirmation_token, $token, $user_id);

if ($user && $user->confirmation_token == $token) {
    $pdo->prepare('UPDATE users SET confirmation_token = NULL, confirmation_date = NOW() WHERE id = ?')->execute([$user_id]);
    $_SESSION['auth'] = $user;
    header('Refresh:3;url=account.php');
    echo("Le compte a été validé avec succès");
    
} else {
    header("Refresh:3; url=login.php");
    echo"Ce token n'est plus valide";
}

?>
