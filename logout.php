<?php

session_start();
setcookie('remember', NULL, -1);
unset($_SESSION['auth']);
header('Refresh:3;url=login.php');
echo"Vous avez été correctement déconnecté";

