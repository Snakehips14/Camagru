<?php

function    debug($var) {
    echo '<pre>' . print_r($var, true) . '</pre>';
}

function    tokenize($len) {
    $alpha = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $token = substr(str_shuffle(str_repeat($alpha, $len)), 0, $len);
    return $token;
}

function denied_access() {
    if (session_status() == PHP_SESSION_NONE) {
	session_start();
    }
    if(!isset($_SESSION['auth'])) {
	header('Refresh:3;url=login.php');
	echo "Vous devez être connecté pour accéder à votre compte.";
	exit();
    }
}

function remember_me() {
    if (session_status() == PHP_SESSION_NONE) {
	session_start();
    }
    if (isset($_COOKIE['remember']) && !isset($_SESSION['auth'])) {
	require_once 'db.php';
	if (!isset($pdo)) {
	    global $pdo;
	}
	$remember_token = $cookie['remember'];
	$parts = explode('==', $remember_token);
	$user_id = $parts[0];
	$req = $pdo->prepare('SELECT * FROM users WHERE id = ?');
	$req->execute([$user_id]);
	$user = $req->fetch();
	if ($user) {
	    $isit = $user_id . '==' . $user->remember_token . sha1($user_id . 'cacamoucacamoucacamagru');
	    if ($isit == $remember_token) {
	        session_start();
	        $_SESSION['auth'] = $user;
		setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7);
	    }
	} else {
	    setcookie('remember', NULL, -1);
	}
    }
}

?>
