<?php
	if(isset($_POST['accept'])) { //On vérifie si l'utilisateur a accepté
		//On crée un cookie pour s'en rappeller et ne plus proposer le formulaire
		setcookie("accept", $_POST['accept'] , time() + 365*24*3600, "/", null, false, true);
	} elseif(isset($_COOKIE['accept'])) { //Si le cookie existe 
			//Je ne fais rien pour que le bandeau ne s'affiche plus
	} else { //Sinon je propose le formulaire
?>
<div align="center">
	<form class="cookies_accept" align="center" id="cookies_accept" method="post" action="">
        <label for="cookies_accept">
        Bonjour, notre site utilise les cookies pour pouvoir fonctionner.<br/> 
        Pour accéder au site, vous devez accepter les cookies.                                
        </label><br/>
        <input type="hidden" name="accept" id="accept" value="accept">
        <input class="form_button" type="submit" name="Envoyer" value="Accepter">
    </form>  
</div>
<?php
}
?>
