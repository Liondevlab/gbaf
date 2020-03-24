<?php
//Bouton de déconnexion du header
session_start();
session_destroy();
header('location: index.php');
exit;
?>
