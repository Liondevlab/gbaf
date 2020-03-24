<?php
session_start();
?>
<?php
include('cookies_accept.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Page de connexion GBAF</title>
        <meta name="viewport" content="width=device-width"/>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <?php //On ouvre la base de données et on vérifie que les variables Post utilisateur et mot de passe existent puis on les mets dans des variables normales
        include('openbdd.php');
        if (isset($_SESSION['username'])) {
                $username = trim(htmlspecialchars($_SESSION['username']));
            } else { 
                $username="";
            }
            
            if(isset($_POST['password'])) { 
                $password = trim(htmlspecialchars($_POST['password']));
            }
            
            if(isset($_POST['password2'])) {
                $password2= trim(htmlspecialchars($_POST['password2']));
            }
        
        if(isset($password, $password2)) 
            {
                if(empty($password)) { //On vérifie si le mot de passe est vide
                    ?>
                    <p align="center">
                    Vous devez renseigner un mot de passe.<br/>
                    <div align="center" class="warning">
                        <a href="./Lost_password.php">Retour à la page de réinitialisation du mot de passe</a><br/>
                        <a href="./index.php">Ou à la page de connexion.</a>
                    </div>
                    </p>
                    <?php
                } elseif(!preg_match("#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$#",$password)) { //On vérifie que le mot de passe respecte les règle de création
                    ?>
                    <div align="center" class="warning">
                        <p>Votre mot de passe ne doit contenir que des lettres majuscules, minuscules, des chiffres, des caractères spéciaux et doit être d'une longueur minimum de 8 caractères"</p>
                        <a align="center" href="lost_password.php">Retour à la page réinitialisation de mot de passe.</a>
                        <a href="./index.php">Ou à la page de connexion.</a>
                    </div>
                    <?php
                } elseif($password2 != $password) { //On vérifie si les mot de passe sont différents
                    ?>
                    <div align="center" class="warning">
                        <p>Les mots de passe ne correspondent pas.</p>
                        <a align="center" href="./index.php">Retour à la page réinitialisation de mot de passe.</a>
                        <a href="./index.php">Ou à la page de connexion.</a>
                    </div>
                    <?php
            } else { //On hash le mot de passe puis on met à jour l'utilisateur dans la base de données
                $password = password_hash($password, PASSWORD_DEFAULT);
                $res = $bdd->prepare('UPDATE account SET password = :password WHERE username = :username');
                $res->bindValue(':username',   $username, PDO::PARAM_STR);
                $res->bindValue(':password',   $password, PDO::PARAM_STR);
                    
                    if ($res->execute()) { //On envoi un message pour avertir si c'est bon ou pas
                        ?>
                        <div align="center" class="form_ok">
                            <p>"Votre mot de passe a bien été changé, vous pouvez retourner à la page d'accueil pour vous connecter."</p><br/>
                            <a href="./index.php">Retour à la page de connexion.</a>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div align="center" class="warning">
                            <p>Une erreur s'est produite... Veuillez reessayer.</p><br/>
                            <a href="./index.php">Retour à la page de connexion.</a>
                        </div>
                        <?php
                    }
                }
            }

        if  (isset($_POST['reponse'])) { //On vérifie si la réponse a été envoyé et qu'elle est bonne
        $reponse = trim(htmlspecialchars($_POST['reponse']));
        $username = $_SESSION['username'];
        $req = $bdd->prepare('SELECT reponse FROM account WHERE username = ?'); 
        $req->execute(array($username));
        $resultat = $req->fetch();
        $isreponseok = password_verify($reponse, $resultat['reponse']);
            if (isset($isreponseok)) //On vérifie si la reponse a bien été vérifiée
            {
                ?> <!--   On affiche donc le formulaire de changement de mot de passe   -->
                <p class="logo_lostpwd" align="center"><a href="index.php"><img src="files/LogoGBAF_texte.PNG" alt="Logo GBAF" /></a></p><br/>
                <div class="form_lostpwd">
                    <div>
                    </div>
                    <div class="form_lostpwd_bloc">
                    <form id="connexion" action="" method="post">
                        <p align="center">
                        <label>Choisissez votre nouveau mot de passe :</label><br/>
                        <input class="fields" type="password" name="password" autofocus/><br/>
                        <label>Veuillez retaper votre mot de passe :</label><br/>
                        <input class="fields" type="password" name="password2" /><br/>
                        <input class="form_button" type="submit" value="Valider" /><br/>
                        </p>
                    </form>
                    </div>
                    <div>
                    </div>
                </div>
                </div>
                    <div class="retour_accueil" align="center">
                    <a href="index.php">Retour...</a>
                </div> 
                <?php
            }
            else
            {
                ?>
                <div align="center" class="warning">
                    <strong><p>Mauvaise réponse!</p></strong> <br/>
                    <a href="lost_password.php">Retour...</a>
                </div>
                <?php
            }
        }    
        elseif(isset($_POST['username'])) { //On cherche a afficher la question de l'utilisateur depuis la base de données
            $username = trim(htmlspecialchars($_POST['username']));
            $req = $bdd->prepare('SELECT question, reponse FROM account WHERE username = ?');
            $req->execute(array($username));
            if ($resultat = $req->fetch()) { //si on obtient la question on affiche le formulaire pour y répondre
                ?>
                <p align="center"><a href="index.php"><img src="files/LogoGBAF_texte.PNG" alt="Logo GBAF" /></a></p><br/>
                <div class="form_lostpwd">
                    <div>
                    </div>
                    <div class="form_lostpwd_bloc">
                        <form id="quest_rep" target="" method="post">
                            <p align="center">
                            <label>Répondez à votre question secrète.</label><br/>
                            <label><?php echo '<strong>' .$resultat['question']. '</strong>' ; ?></label><br/>
                            <input class="fields" type="password" name="reponse" autofocus/><br/>            
                            <input class="form_button" type="submit" value="Valider" /><br/>
                            </p>
                        </form>
                    </div>
                    <div>
                    </div>
                </div>
                    <div class="retour_accueil" align="center">
                    <a href="index.php">Retour...</a>
                </div> 
            <?php
                $_SESSION['username'] = trim(htmlspecialchars($username));
                } else {
                    ?>
                    <div class="warning" align="center">
                        <strong> Nom d'utilisateur incorrect </strong>
                        <a href="Lost_password.php">Retour...</a>
                    </div>
                    <?php
                }
        }
        else
        { //Si le nom d'utilisateur n'est pas dans la variable on affiche le formulaire qui demande le nom d'utilisateur
        ?>  
            <p align="center"><a href="index.php"><img src="files/LogoGBAF_texte.PNG" alt="Logo GBAF" /></a></p><br/>
            <div class="form_lostpwd">
                <div>
                </div>
                <div class="form_lostpwd_bloc">
                    <form id="lost_password" action="" method="post">
                        <p align="center">
                        <label>Entrez votre nom d'utilisateur :</label><br/>
                        <input class="fields" type="text" name="username" autofocus/><br/>
                        <input class="form_button" type="submit" name="Valider">
                        </p>
                    </form>
                </div>
                <div>
                </div>
            </div>
            <div class="retour_accueil" align="center">
                <a href="index.php">Retour...</a>
            </div>                      
        <?php
        }
        ?>
    </body>
</html>
