<?php 
ob_start();
require 'functions.php';
require 'header.php';
require 'db.php';
denied_access();
remember_me();

$uppercase = preg_match('@[A-Z]@', $_POST['password']);
$lowercase = preg_match('@[a-z]@', $_POST['password']);
$number    = preg_match('@[0-9]@', $_POST['password']);

if (isset($_POST['passwd'])) {
    if (empty($_POST['password_confirm']) || empty($_POST['password']) || !$uppercase || !$lowercase || !$number || strlen($_POST['password']) < 8 || strlen($_POST['password']) > 15 || $_POST['password'] != $_POST['password_confirm']) {
    	echo "Le mots de passe ne correspondent pas ou ce dernier n'est pas bien formaté (il doit doit être composé d'au moins 8 caractère et contenir au minimum un chiffre et une majuscule).\n";
    } 
    else {
    	$user_id = $_SESSION['auth'];
	$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
	$user = $pdo->prepare('UPDATE users SET password = ?')->execute([$password]);;
    	echo "Votre mot de passe a bien été mis à jour";
    }
}


if (isset($_POST['usrname'])) {
    $nb_usr = "SELECT * FROM users WHERE username= :username";
    if (empty($_POST['username']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) {
	echo "Veuillez rentrer un nom d'utilisateur valide (il doit contenir des chiffres et des lettres)";
    } elseif ($nb_usr == 1) {
	echo "Ce nom d'utilisateur est déjà utilisé";
    } else {
	$user_id = $_SESSION['auth'];
    	$user = $pdo->prepare('UPDATE users SET username = ?')->execute([$_POST['username']]);
	echo "Votre nom d'utilisateur a bien été mise à jour";
    }
}

if (isset($_POST['addr_email'])) {
    $masque = "/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,4}$/";
    $sql = 'SELECT count(*) FROM users WHERE email= ?';
    $req = $pdo->prepare($sql);
    $req->execute([$_POST['email']]);
    $email = $req->fetch;
    if (empty($_POST['email']) || !preg_match($masque, $_POST['email'])) {
	echo "Veuillez rentrer une adresse Email valide";
    } elseif ($row) {
	echo "Un compte est déjà associé à cet email";
    } else {
	$user_id = $_SESSION['auth'];
	$user = $pdo->prepare('UPDATE users SET email = ?')->execute([$_POST['email']]);
	echo "Votre adresse email a bien été mise à jour";
    }
}

    
?>
    <h1>Bienvenue sur votre compte <?= $_SESSION['auth']->username; ?></h1>
	<form action="" method="post">
	    <div class="hey">
		Nouveau mot de passe : <input type="password" name="password" placeholder"Changer de mot de passe"/>
	    </div>
	    <div class="hey">
		Confirmation du mot de passe : <input type="password" name="password_confirm" placeholder"Confirmer le mot de passe"/>
	    </div>
		<input type="submit" name="passwd" value="Changer mon mot de passe">
	</form>
	<br/><br/>
	<form action="" method="post">
	    <div class="hey">
		Nouveau nom d'utilisateur : <input type="text" name="username" placeholder"Changer de nom d'utilisateur"/>
	    </div>
		<input type="submit" name="usrname" value="Changer mon nom d'utilisateur">
	</form>
	<br/><br/>
	<form action="" method="post">
	    <div class="hey">
		Nouvelle adresse email : <input type="email" name="email" placeholder"Changer d'adresse email"/>
	    </div>
		<input type="submit" name="addr_email" value="Changer mon adresse email">
	</form>

<?php require 'footer.php' ?>
