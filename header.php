<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
?>

<!doctype html>

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>Project</title>    
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/css/app.css" rel="stylesheet">
<title>Camagru</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
	<a class="navbar-brand" href="header.php">Camagru</a>
	    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	    </button>
	    <div class="collapse navbar-collapse" id="navbarNav">
		<ul class="navbar-nav">
		    <?php if (isset($_SESSION['auth'])): ?>
		    <li class="nav-item">
			<a class="nav-link" href="index.php">Home</a>
		    </li>
		    <li class="nav-item">
			<a class="nav-link" href="account.php">Mon compte</a>
		    </li>
		    <li class="nav-item">
			<a class="nav-link" href="logout.php">Se déconnecter</a>
		    </li>
		    <?php else: ?>
		    <li class="nav-item">
			<a class="nav-link" href="inscription.php">S'inscrire</a>
		    </li>
		    <li class="nav-item">
			<a class="nav-link" href='login.php'>Se Connecter</a>
		    </li>
		    <?php endif; ?>
		</ul>
	    </div>
    </nav>
</body>
