<?php ob_start(); ?>

<?php
    $uppercase = preg_match('@[A-Z]@', $_POST['password']);
    $lowercase = preg_match('@[a-z]@', $_POST['password']);
    $number    = preg_match('@[0-9]@', $_POST['password']);

    if (isset($_GET['id']) && isset($_GET['token'])) {
	require 'db.php';
	$req = $pdo->prepare('SELECT * FROM users WHERE id = ? AND forgotten_token = ? AND forgotten_date > DATE_SUB(NOW(), INTERVAL 60 MINUTE)');
	$users = $req->execute([$_GET['id'], $_GET['token']]);
	$user = $req->fetch(PDO::FETCH_OBJ);
	require 'functions.php';
    	if ($user) {
	    if (!empty($_POST)) {
	    	if (!empty($_POST['password']) && $uppercase && $lowercase && $number && strlen($_POST['password']) > 8 && strlen($_POST['password']) < 15 && $_POST['password'] == $_POST['confirm_password']) {
		    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
		    $pdo->prepare('UPDATE users SET password = ?, forgotten_date = NULL, forgotten_token = NULL')->execute([$password]);
		    session_start();
   		    echo "Votre mot de passe a bien été modifié";
	 	    $_SESSION['auth'] = $user;
		    header ('Refresh:3;url=account.php');
		    exit();
		} else {
		      echo "Le mots de passe ne correspondent pas ou ce dernier n'est pas bien formatés (il doit doit être composé d'au moins 8 caractère et contenir au minimum un chiffre et une majuscule).\n";
		}
	    }
	}
    } else {
	require 'functions.php';
	var_dump($_GET['token']);
	session_start();
	echo "Ce token n'est plus valide";
	header('Location: login.php');
	exit();
    }
?>

<?php require 'header.php'; ?>

<html>
    <body>
	<h1>Réinitialisation du mot de passe</h1>
	    <div id="formulaire">
		<form method=POST action="">
		    Nouveau mot de passe : <br /><br /><input type="password" name="password"/><br /><br />
		    Confirmation du mot de Passe : <br /><br /><input type="password" name="confirm_password"/><br /><br />
		    <input type="submit" value="Confirmer">
    </body>
</html>
