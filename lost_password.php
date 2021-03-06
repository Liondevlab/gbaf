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
        <title>Page de connexion GBAF</title>
        <meta name="viewport" content="width=device-width"/>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <p class="logo_lostpwd"><a href="index.php"><img class="logo_gbaf" src="files/LogoGBAF_texte.PNG" alt="Logo GBAF" /></a></p><br/>
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
        
        if(isset($password, $password2)) {
                if(empty($password)) { //On vérifie si le mot de passe est vide
                    ?>
                    <p>
                    Vous devez renseigner un mot de passe.<br/>
                    <div class="warning">
                        <a href="./Lost_password.php">Retour à la page de réinitialisation du mot de passe</a><br/>
                        <a href="./index.php">Ou à la page de connexion.</a>
                    </div>
                    </p>
                    <?php
                } elseif(!preg_match("#^(?=.{10,}$)(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?\W).*$#",$password)) { //On vérifie que le mot de passe respecte les règle de création
                    ?>                 
                    <div class="warning">
                        <p>"Votre mot de passe DOIT contenir au moins une majuscule, une minuscule, un chiffre, un caractère spécial et doit faire au minimum 8 caractères."</p>
                        <a align="center" href="lost_password.php">Retour à la page réinitialisation de mot de passe.</a>
                        <a href="./index.php">Ou à la page de connexion.</a>
                    </div>
                    <?php
                } elseif($password2 != $password) { //On vérifie si les mot de passe sont différents
                    ?>
                    <div class="warning">
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
                    $password_changed = 1;
                } else {
                    $password_changed = 2;
                }
            }
        }

        if  (isset($_POST['reponse'])) { //On vérifie si la réponse a été envoyé et qu'elle est bonne
        $reponse = trim(htmlspecialchars($_POST['reponse']));
        $username = $_SESSION['username'];
        $req = $bdd->prepare('SELECT reponse FROM account WHERE username = :username'); 
        $req->execute(array('username' => $username));
        $resultat = $req->fetch();
        $isreponseok = password_verify($reponse, $resultat['reponse']);
            if ($isreponseok) //On vérifie si la reponse a bien été vérifiée
            {
                ?> <!--   On affiche donc le formulaire de changement de mot de passe   -->
                <p class="signup_explanation">* Votre mot de passe DOIT contenir au moins une majuscule, une minuscule, un chiffre, un caractère spécial et doit faire au minimum 8 caractères.</p>
                <div class="form_lostpwd">
                    <div>
                    </div>
                    <div class="form_lostpwd_bloc">
                    <form id="connexion" action="lost_password.php" method="post">
                        <p>
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
                    <div class="retour_accueil">
                    <a href="index.php">Retour...</a>
                </div> 
                <?php
            }
            else
            {
                ?>
                <div class="warning">
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
                <div class="form_lostpwd">
                    <div>
                    </div>
                    <div class="form_lostpwd_bloc">
                        <form id="quest_rep" target="" method="post">
                            <p>
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
                    <div class="retour_accueil">
                    <a href="index.php">Retour...</a>
                </div> 
            <?php
                $_SESSION['username'] = trim(htmlspecialchars($username));
                } else {
                    ?>
                    <div class="warning">
                        <strong> Nom d'utilisateur incorrect </strong>
                        <a href="Lost_password.php">Retour...</a>
                    </div>
                    <?php
                }
        }
        else
        { //Si le nom d'utilisateur n'est pas dans la variable on affiche le formulaire qui demande le nom d'utilisateur
            if (isset($password_changed)) {
                if ($password_changed == 1) {            
                    ?>
                    <div class="form_ok">
                        <p>"Votre mot de passe a bien été changé, vous pouvez retourner à la page d'accueil pour vous connecter."</p><br/>
                        <a href="./index.php">Retour à la page de connexion.</a><br/>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="warning">
                        <p>Une erreur s'est produite... Veuillez reessayer.</p><br/>
                        <a href="./index.php">Retour à la page de connexion.</a><br/>
                    </div>
                    <?php
                }
            }            
            ?>
            <div class="form_lostpwd">
                <div>
                </div>
                <div class="form_lostpwd_bloc">
                    <form id="lost_password" action="lost_password.php" method="post">
                        <p>
                        <label>Entrez votre nom d'utilisateur :</label><br/>
                        <input class="fields" type="text" name="username" autofocus/><br/>
                        <input class="form_button" type="submit" name="Valider">
                        </p>
                    </form>
                </div>
                <div>
                </div>
            </div>
            <div class="retour_accueil">
                <a href="index.php">Retour...</a>
            </div>                      
        <?php
        }
        ?>
    </body>
</html>
