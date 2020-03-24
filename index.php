<?php
session_start();
?>
<?php
include('cookies_accept.php');
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="./style.css" />
        <meta name="viewport" content="width=device-width"/>
        <title>Page de connexion GBAF</title>
    </head>
    <body>
        <p>
            <a href="https://jigsaw.w3.org/css-validator/check/referer">
                <img style="border:0;width:88px;height:31px"
                src="https://jigsaw.w3.org/css-validator/images/vcss-blue"
                alt="CSS Valide !" />
            </a>
        </p>
        <?php
            // vérification présence des variables $_POST
            if (isset($_POST['username'], $_POST['password'])) {
                // ouverture de la base de données
                include('openbdd.php');
                    //  Récupération des données $_POST et message en cas d'erreur
                    if (empty($_POST['username'])) {
                        ?>
                        <p class="warning" align="center"><strong>Nom d'utilisateur ou mot de pass incorrect...</strong></p>
                        <?php
                    } else {
                        $username = trim(htmlspecialchars($_POST['username']));
                    }
                    if (empty($_POST['password'])) {
                        ?>
                        <p class="warning" align="center"><strong>Nom d'utilisateur ou mot de pass incorrect...</strong></p>
                        <?php
                    } else {
                        $password = trim(htmlspecialchars($_POST['password']));
                    }
                    if (empty($username)) {
                        ?>
                        <p class="warning" align="center"><strong>Nom d'utilisateur ou mot de pass incorrect...</strong></p>
                        <?php
                    } else {
                    //  Récupération de l'utilisateur et de son mot de passé hashé
                    $req = $bdd->prepare('SELECT id_user, nom, prenom, password FROM account WHERE username = :username');
                    $req->execute(array('username' => $username));
                    $resultat = $req->fetch();
                    }
                    // Comparaison du mot de passe envoyé via le formulaire avec la bdd
                    if (!empty($resultat['password'])) {
                        $isPasswordCorrect = password_verify($password, $resultat['password']);
                    }

                    if (!empty($resultat)) //Si le résultat n'est pas vide on ouvre la session et on envoi l'utilisateur sur la page home.php
                    {
                        if ($isPasswordCorrect) {
                            session_start();
                            $_SESSION['id_user'] = $resultat['id_user'];
                            $_SESSION['username'] = $username;
                            $_SESSION['nom'] = $resultat['nom'];
                            $_SESSION['prenom'] = $resultat['prenom'];
                            setcookie("username", $_SESSION['username'] , time() + 365*24*3600, "/", null, false, true);
                            header('Location: home.php');
                            exit();
                        } else { // Message si le mot de passe est incorrect et proposition d'aller sur la page de réinitialisation de mot de passe
                            ?>
                            <p class="warning" align="center"><strong>Mot de passe incorrect...</strong></p>
                            <p id="links" align="center"><a href="index.php">Cliquez ici pour retourner à la page de connexion...</a></p>
                            <p id="links" align="center">Si vous rencontrez des difficultés avec votre mot de passe vous pouvez le réinitialiser <a href="lost_password.php">sur cette page...</a></p>
                            <?php
                        }
                    } else { // Message si le nom d'utilisateur est incorrect
                        ?>
                        <p class="warning" align="center"><strong>Nom d'utilisateur incorrect...</strong></p>
                        <p id="links" align="center"><a href="index.php">Cliquez ici pour retourner à la page de connexion...</a></p>
                        <?php
                    }

                } else {    
                    ?>
                    <!--Logo et formulaire de connexion-->
                    <div class="index_logo">
                    <p><img class="index_logo" src="./files/logogbaf_texte.png" alt="Logo GBAF" /></p><br/>
                    <p class="bonjour">Bonjour. Veuillez vous connecter s'il vous plait :</p>
                    </div>    
                        <?php
                        if ((isset($_GET['vide'])) && ($_GET['vide']) == 1) {
                        ?>
                        <p class="warning"><strong>Nom d'utilisateur ou mot de pass incorrect...</strong></p>
                        <?php    
                        }
                        ?>
                    <form id="connexion" action="index.php" method="post">
                        <p class="login">
                        <label>Nom d'utilisateur :</label><br/>
                            <?php
                            if (isset($_COOKIE['username'])) {
                                ?><input class="fields" type="text" name="username" value="<?php echo $_COOKIE['username']; ?>" /><br/>
				            <label>Mot de passe :</label><br/>
                        	<input class="fields" type="password" name="password" autofocus/><br/>
                        	<input class="form_button" type="submit" value="Valider" /><br/>                                
				            <?php
                            } else {
                                ?><input class="fields" type="text" name="username" autofocus/><br/>
				            <label>Mot de passe :</label><br/>
                        	<input class="fields" type="password" name="password" autofocus/><br/>
                        	<input class="form_button" type="submit" value="Valider" /><br/>  
                                <?php
                            }
                            ?>
                        </p>
                    </form>
                    <p class="signup_link" id="linksignup">Si vous n'êtes pas encore inscrit, <a href="signup.php">cliquez ici.</a></p>
                    <p class="reset_link" id="linklostpwd" ><a href="lost_password.php">Mot de passe oublié?</a></p>
                    <?php
                }
            ?>
    </body>
</html>
