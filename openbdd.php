<?php
	//Ouverture de la base de données à mettre en include
	try
	    {
	            $bdd = new PDO('mysql:host=127.0.0.1;dbname=gbaf;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	    }
	catch (Exception $e)
	    {
	            die('Erreur : ' . $e->getMessage());
    	}
?>
