<?php
function isLoginSessionExpired() {
	$login_session_duration = 2700;//Réglage du timout à 45 minutes
	if(isset($_SESSION['loggedin_time']) and isset($_SESSION['id_user'])){  
		if(((time() - $_SESSION['loggedin_time']) > $login_session_duration)){ 
			return true; 
		} 
	}
	return false;
}
?>
