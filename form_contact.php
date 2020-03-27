<?php
include('header.php');
include('cookies_accept.php');
include('openbdd.php');
include('timeout.php');
if(isset($_SESSION['user_id'])) {
    if(isLoginSessionExpired()) {
        header("Location:disconnect.php?session_expired=1");
    }
}    
    // Code PHP pour traiter l'envoi de l'email
    ?>
    <div class="body_contact">  
    <?php
            $nombreErreur = 0; // Variable qui compte le nombre d'erreur
      
    // Définit toutes les erreurs possibles
            if (!isset($_POST['email'])) { // Si la variable "email" du formulaire n'existe pas (il y a un problème)
                $nombreErreur++; // On incrémente la variable qui compte les erreurs
                $erreur1 = '<p>Il y a un problème avec la variable "email".</p>';
             // Sinon, cela signifie que la variable existe (c'est normal)
            } elseif(empty($_POST['email'])) { // Si la variable est vide
                $nombreErreur++; // On incrémente la variable qui compte les erreurs
                $erreur2 = '<p>Vous avez oublié de donner votre email.</p>';
            } elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { // Validation de la forme de l'email
                $nombreErreur++; // On incrémente la variable qui compte les erreurs
                $erreur3 = '<p>Cet email ne ressemble pas un email.</p>';
            } elseif(!isset($_POST['message'])) {
                $nombreErreur++;
                $erreur4 = '<p>Il y a un problème avec la variable "message".</p>';
            } elseif(empty($_POST['message'])) {
                $nombreErreur++;
                $erreur5 = '<p>Vous avez oublié de donner un message.</p>';
            } elseif(!isset($_POST['nom'])) {
                $nombreErreur++;
                $erreur6 = '<p>Il y a un problème avec vos nom et prenom.</p>';
            } elseif($_POST['captcha']!=4) { //si le résultat du captcha n'est pas 4
                $nombreErreur++;
                $erreur7 = '<p>Désolé, le captcha anti-spam est erroné.</p>';
            } elseif(empty($_POST['RGBD'])) {
                $nombreErreur++;
                $erreur8 = '<p>Veuillez accepter les condition de retention des données. </p>';
            } elseif (empty($_POST['nom'])) {
                $nombreErreur++;
                $erreur9 = '<p>Vous n\'avez pas renseigné vos nom et prénom. </p>';
            } elseif (empty($_POST['objet'])) {
                $nombreErreur++;
                $erreur10 = '<p>Vous n\'avez pas renseigné l\'objet de votre message. </p>';
            } elseif(($nombreErreur==0) && (!empty($_POST['envoi']))) { // S'il n'y a pas d'erreur
        
            // Récupération des variables et sécurisation des données
                
                $objet = htmlspecialchars($_POST['objet']); // Titre de l'email
                $nom = htmlspecialchars($_POST['nom']); // Nom de l'utilisateur
                $email = htmlspecialchars($_POST['email']); // email de l'utilisateur
                $message = $_POST['message']; // Contenu du message de l'utilisateur
            
            // Pour envoyer un email HTML, l'en-tête Content-type doit être défini

                $header="MIME-Version: 1.0\r\n";
                $header.='From: "GBAF.liondevlab.com"'."\n";
                $header.='Content-Type:text/html; charset="utf-8"'."\n";
                $header.='Content-Transfer-Encoding: 8bit';
                
            // Variables concernant le contenu html de l'email incluant les infos du formulaire
                
                $contenu = '<html><head><title>Formulaire utilisateur GBAF</title></head><body>';
                $contenu .= '<p>Bonjour, vous avez reçu un message d\'un utilisateur à partir de votre site GBAF.</p>';
                $contenu .= '<p><strong>Nom</strong>: '.$nom.'</p>';
                $contenu .= '<p><strong>Email</strong>: '.$email.'</p>';
                $contenu .= '<p><strong>Message</strong>: </p><br/>';
                $contenu .= '<p>'.$message.'</p>';
                $contenu .= '<span style="text-align: right;"><em>Le webmaster</em></span>';
                $contenu .= '</body></html>'; // Contenu du message de l'email
            
                // Fonction principale qui envoi l'email
                ?>
        
                    <?php
                    if(isset($_SESSION['sended'])) {
                        ?>
                        <p class="warning"> Le mail est déja parti </p>
                        <?php
                        unset($_SESSION['sended']);
                    } else {
                        $_SESSION['sended'] = true;
                        unset($_POST);
                        mail("julien.guerard@liondevlab.com", $objet, $contenu, $header); // Si le mail part
                        ?>
                    <?php   
                    }
                   
//===================================================================================================================

            } else { // S'il y a un moins une erreur on récupère le total de la variable $nombreErreur et on envoi le message correspondant
                echo '<div style="border:1px solid #ff0000; padding:5px;">';
                echo '<p style="color:#ff0000;">Désolé, il y a eu '.$nombreErreur.' erreur(s). Voici le détail des erreurs:</p>';
                if (isset($erreur1)) {
                    echo '<p>'.$erreur1.'</p>';
                }
                if (isset($erreur2)) {
                    echo '<p>'.$erreur2.'</p>';
                }
                if (isset($erreur3)) {
                    echo '<p>'.$erreur3.'</p>';
                }
                if (isset($erreur4)) {
                    echo '<p>'.$erreur4.'</p>';   
                }
                if (isset($erreur5)) {
                    echo '<p>'.$erreur5.'</p>';
                }
                if (isset($erreur6)) {
                    echo '<p>'.$erreur6.'</p>';
                }
                if (isset($erreur7)) {
                    echo '<p>'.$erreur7.'</p>';
                }
                if (isset($erreur8)) {
                    echo '<p>'.$erreur8.'</p>';
                }
                if (isset($erreur9)) {
                    echo '<p>'.$erreur9.'</p>';
                }
                if (isset($erreur10)) {
                    echo '<p>'.$erreur10.'</p>';
                }
            }

//===================================================================================================================
   
        // afficher le formulaire avec le champs "nom" prérempli et un exemple de mail
            $req = $bdd->prepare('SELECT nom, prenom FROM account WHERE id_user = :id_user');
            $req->execute(array('id_user' => $_SESSION['id_user']));
            $resultat = $req->fetch();
            $prenom = $resultat['prenom'];
            $nom = $resultat['nom'];
        ?>
        <h3 class="contact_title"> Contactez nous </h3>
        <?php
        if (isset($_SESSION['sended'])) {
            if ($_SESSION['sended'] == 1) {
            ?> <h3 class="form_ok" align="center">Le formulaire a bien été envoyé</h3> <?php
            }
        }
        ?>
        <div class="warning">
            * Merci de remplir tout les champs pour que formulaire puisse partir.
        </div>
            <div class="contact_form">
                <div>
                    
                </div>
                <div>
                <form id="contact" method="post" action="form_contact.php">
                    <fieldset class="field_coordinate"><legend>Vos coordonnées</legend>
                        <p><label for="nom">Nom & Prénom: <span style="color:#ff0000;">*</span> <br/> </label><input class="fields" type="text" id="nom" name="nom" value="<?php echo $nom; echo " "; echo $prenom; ?>" /></p>
                        <p><label for="email">Email : <span style="color:#ff0000;">*</span> <br/> </label><input class="fields" type="text" id="email" name="email" placeholder="email@domaine.com" autofocus/></p>
                    </fieldset>
                 
                    <fieldset class="field_message"><legend>Votre message :</legend>
                        <p><label for="objet">Objet : <span style="color:#ff0000;">*</span> <br/> </label><input class="fields" type="text" id="objet" name="objet" placeholder="Objet..." /></p>
                                <!-- (2): textarea will replace by CKEditor -->
                        <p><label for="message">Message : <span style="color:#ff0000;">*</span> <br/> </label><textarea class="contact_field" id="message" name="message" cols="30" rows="8" placeholder="Ecrivez votre message ici..."></textarea></p>
                         <!-- (3): Javascript code to replace textarea with id='editor1' by CKEditor -->
                        <div class="cke_form">
                        <script>
                            CKEDITOR.replace( 'message', {
                            uiColor: '#FF0000',
                            toolbar: [
                               [ 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink' ],
                               [ 'FontSize', 'TextColor', 'BGColor' ]
                            ]});
                        </script>
                        </div>
                    </fieldset>
                    <div class="rgbd">
                        <i><input type="checkbox" name="RGBD" id="RGBD" /></i> <label for="RGBD"> <span style="color:#ff0000;">*</span> J'accepte que mes données personnelles soient collectées</label> <br/>
                    </div>
                    <p class="captcha_txt">Combien font 1+3: <span style="color:#ff0000;">*</span>: <input class="captcha" type="text" name="captcha" size="2" /></p>
                    <div><input class="form_button" type="submit" name="envoi" value="Envoyer le formulaire !" /></div>
                </form>
                </div>
                <div>
                    
                </div>
            </div>
    </div>
    <?php
    include('footer.php');
    ?>
