<?php
session_start()
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
        <?php
        include('openbdd.php');
        //On vérifie si les valeur Post existent

        $error = []; // tableau pour stocker les erreurs
        $post = [];
        $showErrors = false; // Par défaut, on ne vera pas afficher les erreurs

        if (!empty($_POST)) { //On vérifie si les valeur Post ne sont pas vide
            foreach ($_POST as $key => $value) {
                $post[$key] = trim(htmlspecialchars($value));
            }
            
            //On vérifie que tout les variables sont conformes
                
                if(!preg_match("#^(?=.{2,}$).*$#",$post['nom'])) { //On vérifie si le nom comporte 2 caractères ou plus
                    $error[] = '<p class="warning" align="center">Le nom doit faire 2 caractères ou plus.</p>';
                }
                if(!preg_match("#^(?=.{2,}$).*$#",$post['prenom'])) { //On vérifie si le prénom comporte 2 caractères ou plus
                    $error[] = '<p class="warning" align="center">Le prénom doit faire 2 caractères ou plus.</p>';
                }
                if(!preg_match("#^(?=.{3,20}$).*$#",$post['username'])) { //On vérifie si le nom d'utilisateur comporte entre 4 et 20 caractères
                    $error[] = '<p class="warning" align="center">Le nom d\'utilisateur doit faire entre 4 et 20 caractères.</p>';                
                }
                if(!empty($post['username'])) { //On vérifie si la donnée n'est pas vide
                    $req = $bdd->query('SELECT username FROM account WHERE username="'.$post['username'].'"');
                    $chk_username = $req->fetch(PDO::FETCH_ASSOC);
                    
                    if(!empty($_POST) && $chk_username == '1' || $chk_username > '1') { //On vérifie si le nom d'utilisateur est déja pris
                    $error[] = '<p class="warning" align="center">Ce nom d\'utilisateur est déjà pris....</p>';
                    }                
                }
                if(!preg_match("#^(?=.{8,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#",$post['password'])) { //On vérie que le mot de passe ne comporte que 8 caractères minimum, des lettres, des chiffres et des caractères spéciaux
                    $error[] = '<p class="warning" align="center">Votre mot de passe doit contenir au une lettre majuscule, une minuscule, un chiffre, un caractère spécial et doit être d\'une longueur minimum de 8 caractères.</p>';                
                }
                if($post['password2'] != $post['password']) { //On vérifie si les mot de passe sont différent
                    $error[] = '<p class="warning" align="center">Les mots de passe ne correspondent pas.</p>';                
                }
                if(empty($post['question'])) { //On vérifie que la question n'est pas vide
                    $error[] = '<p class="warning" align="center">Votre question ne doit pas être vide.</p>';                
                }
                if(empty($post['reponse'])) { //On vérifie que la reponse n'est pas vide
                    $error[] = '<p class="warning" align="center">Votre réponse ne doit pas être vide.</p>';                
                }
                if(count($error) > 0 ) {
                        $showErrors = true;
                        $nom  = $post['nom'];
                        $prenom   = $post['prenom'];
                        $username     = $post['username'];
                        $question   = $post['question'];
                } else {                    
                    $post['password2'] == $post['password'];
                    $post['password'] = password_hash($post['password'], PASSWORD_DEFAULT); 
                    $post['reponse'] = password_hash($post['reponse'], PASSWORD_DEFAULT);
                    $res = $bdd->prepare('INSERT INTO account(nom, prenom, username, password, question, reponse) VALUES(:nom, :prenom, :username, :password, :question, :reponse)');
                    $res->bindvalue(':nom',   $post['nom'], PDO::PARAM_STR);
                    $res->bindValue(':prenom',   $post['prenom'], PDO::PARAM_STR);
                    $res->bindValue(':username',   $post['username'], PDO::PARAM_STR);
                    $res->bindValue(':password',   $post['password'], PDO::PARAM_STR);
                    $res->bindValue(':question',   $post['question'], PDO::PARAM_STR);
                    $res->bindValue(':reponse',   $post['reponse'], PDO::PARAM_STR);
                    if($res->execute()) {
                        ?>
                        <div align="center" class="signup_success">
                        <p class="modif_success">"Vous êtes maintenant inscrit, vous pouvez retourner à la page d'accueil pour vous connecter."</p><br/>
                        <a href="index.php" align="center">Retour à la page de connexion.</a>
                        </div>
                        <?php
                    } else { var_dump($res->erroInfo());}
                }
            }
                    ?>
                    <div class="logo_index">
                        <p><a href="./index.php"><img src="./files/LogoGBAF_texte.PNG" alt="Logo GBAF" /></a></p><br/>
                    </div>
                    <p class="signup_txt">Merci d'entrer les informations suivantes pour pouvoir vous enregistrer :</p>
                    <?php
                    if ($showErrors) {
                        echo '<p class="warning" align="center">Merci de vérifier les informations suivantes :</p></br>' . implode('</br>', $error);
                    }
            ?>
            <p class="signup_explanation">
                <strong>Afin de vous inscrire, il faut respecter certaines règles :</strong><br/>
                1* Votre nom doit faire 2 caractères ou plus.<br/>
                2* Votre prénom doit faire 2 caractères ou plus.<br/>
                3* Votre nom d'utilisateur doit faire entre 4 et 20 caractères.<br/>
                4* Votre mot de passe doit contenir au une lettre majuscule, une minuscule, un chiffre, <br/>
                un caractère spécial et doit être d'une longueur minimum de 8 caractères.<br/>
                5* Votre question doit faire 5 caractères ou plus.<br/>
                6* Votre réponse doit faire 3 caractères ou plus.<br/>
            </p>
            <div class="inscription">
                <div>
                            
                </div>
                <!--  Formulaire d'inscription  -->
                <div class="form_inscription">
                    <form id="connexion" action="signup.php" method="post">
                        <p>
                        <label>Nom :</label><br/>
                        <input class="fields" type="text" name="nom" autofocus placeholder="Votre nom" value="<?= $nom;?>" /><br/>
                        <label>Prénom :</label><br/>
                        <input class="fields" type="text" name="prenom" placeholder="Votre prénom" value="<?= $prenom;?>" /><br/>
                        <label>Nom d'utilisateur :</label><br/>
                        <input class="fields" type="text" name="username" placeholder="Nom d'utilisateur" value="<?= $username;?>" /><br/>
                        <label>Choisissez votre mot de passe :</label><br/>
                        <input class="fields" type="password" name="password" /><br/>
                        <label>Veuillez retaper votre mot de passe :</label><br/>
                        <input class="fields" type="password" name="password2" /><br/>
                        <label>Tapez votre question secrète en cas de perte de mot de passe :</label><br/>
                        <input class="fields" type="text" name="question" placeholder="Votre question" value="<?= $question;?>" /><br/>
                        <label>Tapez la réponse à votre question secrète :</label><br/>
                        <input class="fields" type="password" name="reponse" /><br/>
                        <input class="form_button" type="submit" value="Valider" /><br/>
                        </p>
                    </form>
                </div>
                <div>            
                </div>
            </div>
            <p class="retour_accueil"><a href="index.php">Retour à la page d'accueil</a></p>
    </body>
</html>
