<?php ob_start(); ?>
<?php require 'header.php'; ?>
<link rel="stylesheet" href="css/app.css">

<?php
require_once 'functions.php';
remember_me();
if (isset($_SESSION['auth'])) {
    header('Location : account.php');
    exit();
}
if (!empty($_POST) && !empty($_POST['username'] && !empty($_POST['password']))) {
    require_once 'db.php';
    $req = $pdo->prepare('SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmation_date IS NOT NULL');
    $req->execute(['username' => $_POST['username']]);
    $user = $req->fetch();
    if (password_verify($_POST['password'], $user->password)) {
    	$_SESSION['auth'] = $user;
	if ($_POST['remember']) {
	    $remember_token = tokenize(250);
	    $pdo->prepare('UPDATE users SET remember_token = ? WHERE id = ?')->execute([$remember_token, $user->id]);
	    setcookie('remember', $user->id . '==' . $remember_token . sha1($user->id . 'cacamoucacamoucacamagru'), time() + 60 * 60 * 24 * 7);
	}
	header('Refresh:3;url=account.php');
	echo"Vous êtes à présent connecté";
	exit();
    } else {
    	echo"Nom d'utilisateur, email ou mot de passe incorrect";
    }
  } else {
    	echo "Veuillez remplir les champs ci dessous";
}

?>

<html>
    <body>
	<h1>Se connecter</h1>
	    <div id="formulaire">
		<form method=POST action="">
		    <div class="form-group">
			<label for="">Nom d'ulisateur ou email</label>
			<input type="text" name="username" class="form-control"/>
		    </div>

		    <div>
			<label for="">Mot de Passe : <a href="forgotten.php">(J'ai oublié mon mot de passe)</a></label>
			<input type="password" name="password" class="form-control"/>
		    </div>

		    <div>
			<label>
			    <input type="checkbox" name="remember" value ="1"/> Se souvenir de moi
			</label>
		    </div>

		    <button type="submit" value="Me connecter" class="form-control">Se connecter</button>
	    </div>
    </body>
</html>
