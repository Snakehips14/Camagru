<?php


if (!empty($_POST)) {

$errors = array();
require_once 'db.php';
require_once 'functions.php';

if (empty($_POST['nom'])) {
    $errors['nom'] = "Veuillez remplir le champs 'Nom'";
}

if (empty($_POST['prenom'])) {
    $errors['prenom'] = "Veuillez remplir le champs 'Prénom'";
}

if (empty($_POST['username']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) {
	$errors['username'] = "Veuillez rentrer un nom d'utilisateur valide (il doit contenir des chiffres et des lettres)";
} else {
    $req = $pdo->prepare('SELECT id FROM users WHERE username = ?');
    $req->execute([$_POST['username']]);
    $user = $req->fetch();
    if ($user) {
	$errors[] = "Ce nom d'utilisateur est déjà pris";
    }
}

$masque = "/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,4}$/";
if (empty($_POST['email']) || !preg_match($masque, $_POST['email'])) {
	$errors['email'] = "Veuillez rentrer une adresse Email valide";
} else {
    $req = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $req->execute([$_POST['email']]);
    $email = $req->fetch();
    if ($email) {
	$errors[] = "Un compte est déjà associé à cet email";
    }
}


$uppercase = preg_match('@[A-Z]@', $_POST['password']);
$lowercase = preg_match('@[a-z]@', $_POST['password']);
$number    = preg_match('@[0-9]@', $_POST['password']);
if (empty($_POST['password']) || strlen($_POST['password']) < 8 || strlen($_POST['password']) > 15 || !$uppercase || !$lowercase || !$number) {
	$errors['password'] = "Veuillez rentrer un mot de passe valide (il doit doit contenir entre 8 et 15 caractères et comprendre au moins un chiffre et une majuscule)";
}

if (empty($_POST['passwd_conf']) || $_POST['password'] != $_POST['passwd_conf']) {
	$errors['passwd_conf'] = "Veuillez retaper le mot de passe à l'identique";
}

if (empty($errors)) {
    $req = $pdo->prepare("INSERT INTO users SET prenom = ?, nom = ?, username = ?, email = ?, password = ?, confirmation_token = ?");
    $token = tokenize(60);
    $passwd = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $req->execute([$_POST['prenom'], $_POST['nom'], $_POST['username'], $_POST['email'], $passwd, $token]);
    $user_id = $pdo->LastInsertId();
    mail($_POST['email'], "Confirmation de votre compte", "Afin de valider votre compte, veuillez cliquez sur le lien suivant :\n\nhttp://localhost:8080/confirm.php?id=$user_id&token=$token");
    header('Refresh:3;url=header.php');
    echo("Le compte a été créé avec succès, un email vous a été envoyé afin de le confirmer");
    exit();
}
}

//debug($errors);

?>
<?php require 'header.php' ?>

<!doctype html>

<html>
    <body>
	<h1>S'inscrire</h1>
	    <?php if (!empty($errors)): ?>
	    <div class="alert alert-danger">
		<p>Vous n'avez pas rempli le formulaire correctement</p>
		<ul>
		    <?php foreach($errors as $error): ?>
		        <li><?= $error; ?></li>
		    <?php endforeach; ?>
		</ul>
	    </div>
	    <?php endif; ?>
	    <div id="formulaire">
	    	<p>Veuillez remplir tous les champs du formulaire d'inscription</p>
		<br/>
		<form method=POST action="">
		    Prénom : <br /><input type="text" name="prenom"/><br /><br />
		    Nom : <br /><input type="text" name="nom"/><br /><br />
		    Nom d'Utilisateur : <br /><input type="text" name="username"/><br /><br />
		    Adresse Email : <br /><input type="email" name="email"/><br /><br />
		    Mot de Passe (il doit doit être composé d'au moins 8 caractère et contenir au minimum un chiffre et une majuscule): <br /><br /><input type="password" name="password"/><br /><br />
		    Répéter le Mot de Passe : <br /><input type="password" name="passwd_conf"/><br /><br />
		    <input type="submit" value="Valider mon inscription">
		</form>
	    </div>
</body>
</html>

<?php require 'footer.php'; ?>
