
<!--   Header contenant le logo à gauche, les infos user à droite et un bouton déconnexion. => à placer en include   -->
<!DOCTYPE html>
<html lang="fr">
	<head>
	    <meta charset="utf-8" />
	    <meta name="viewport" content="width=device-width"/>
	    <link rel="stylesheet" href="style.css" />
        <title>Site du GBAF</title>
        <!-- (1) : Declaration for quill library -->
    	<script src="ckeditor.js"></script>
	</head>
	<body>
		<header>
			<div class="header">
		    	<div class="header_logo">
		    		<a href="home.php"><img class="logo" src="./files/LogoGBAF_texte.png" alt="Logo GBAF" /></a>
		   		</div>
		    
			  	<div class="header_username">
			    	<div class="logo_txt_user">
				   		<p>
				   			<img class="logo_user" src="./files/logo_user.png" alt="logo_user"><strong><?php echo $_SESSION['prenom']; ?> <?php echo $_SESSION['nom'] ?></strong><br/>
				    	</p>
				    </div>
					<div>
						<p>
							<a href="mon_compte.php">Mon compte</a> <br/>
						  	<a href="disconnect.php">Se déconnecter</a>
						</p>
					</div>
				</div>
			</div>
		</header>
		<div class="under_header_space">
			<!--   pour compenser l'espace caché par le fixed header   -->
		</div>
		<p>
			<a href="https://jigsaw.w3.org/css-validator/check/referer">
		    	<img style="border:0;width:88px;height:31px"
		        src="https://jigsaw.w3.org/css-validator/images/vcss-blue"
		        alt="CSS Valide !" />
	    	</a>
		</p>
