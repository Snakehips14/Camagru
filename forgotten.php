<?php ob_start(); ?>
<?php require 'header.php'; ?>

<?php
if (!empty($_POST) && !empty($_POST['email'])) {
    require_once 'db.php';
    require_once 'functions.php';
    $req = $pdo->prepare('SELECT * FROM users WHERE email = ? AND confirmation_date IS NOT NULL');
    $req->execute([$_POST['email']]);
    $user = $req->fetch();
    } else {
    echo"Vous devez rentrer votre email";
    }
    if ($user) {
    	$forgotten_token = tokenize(60);
    	$pdo->prepare('UPDATE users SET forgotten_token = ?, forgotten_date = NOW() WHERE id = ?')->execute([$forgotten_token, $user->id]);
    	header('Refresh:3;url=account.php');
    	echo "Un email vous a été envoyé afin de réinitialiser votre mot de passe";
	mail($_POST['email'], "Réinitialisation de votre mot de passe", "Afin de réinitialiser votre mot de passe, veuillez cliquez sur le lien suivant :\n\nhttp://localhost:8080/reset.php?id={$user->id}&token=$forgotten_token");
    	} else {
	    	echo "Cet email n'appartient à aucun compte";
    }
?>

<h1>Mot de passe oublié</h1>
	<form action="" method="post">
	    <div class="hey">
		Email : <input type="email" name="email" placeholder"Changer de mot de passe"/>
	    </div>
		<input type="submit" value="Réinitialiser mon mot de passe">
	</form>
