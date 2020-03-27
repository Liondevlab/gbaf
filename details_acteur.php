<?php
include('header.php'); 
include('cookies_accept.php');
include('timeout.php');
if(isset($_SESSION['user_id'])) {
    if(isLoginSessionExpired()) {
        header("Location:disconnect.php?session_expired=1");
    }
}
?>

        <?php //on vérifie la présence de l'utilisateur connecté sinon on retourne à l'index
            if (isset($_SESSION['id_user']) AND ($_SESSION['nom']) AND ($_SESSION['prenom']) AND ($_SESSION['username'])) 
            {
                $username = trim(htmlspecialchars($_SESSION['username']));
            } else {
                header('Location: index.php');
            }

        // Connexion à la base de données et récuperation de l'id de l'utilisateur dans une variable
            include('openbdd.php');
            if (empty($_SESSION['id_user'])) {
                header('Location: index.php');
            } else {
                $id_user=$_SESSION['id_user'];
            }
            
        // Récupération de l'acteur dans la base de données grâce à la variable Post
            if (!empty($_POST['id_acteur'])) {
                $id_acteur = trim(htmlspecialchars($_POST['id_acteur']));
                $req = $bdd->prepare('SELECT id_acteur, acteur, description, logo FROM acteur WHERE id_acteur = ?');
                $req->execute(array($id_acteur));
                $donnees = $req->fetch();
        
        //On affiche les détails grâce aux données récoltée
                ?>
        <div class="detail_acteur">
            <div class="logo_acteur_detail" >
                <img class="logo_acteur_detail_img" alt="logo acteur" src="./<?php echo htmlspecialchars($donnees['logo']) ?>">
            </div>
                             
                <h2>
                    <?= $donnees['acteur']; ?>
                </h2>
                <p>
                    <?php
                    echo nl2br(htmlspecialchars($donnees['description']));
                    ?>
                </p>
            <p><a href="home.php">Retour à la liste des acteurs et partenaires...</a></p>
        </div>
        
        <?php
            } else {
                header('Location: home.php');
            }
        ?>
        <div class="box_comm">
            <?php //On vérifie que l'utilisateur n'a pas déja posté un commentaire
                $req = $bdd->prepare('SELECT id_acteur, id_user FROM post WHERE id_acteur = :id_acteur AND id_user = :id_user');
                $req->bindValue(':id_acteur',   $id_acteur, PDO::PARAM_STR);
                $req->bindValue(':id_user',   $id_user, PDO::PARAM_STR);
                $req->execute();
                $resultat = $req->fetch();
                $donnees_acteur = $donnees['id_acteur'];
                if (isset($resultat['id_acteur'])) {
                    $resultat_acteur = $resultat['id_acteur'];
                } else {
                    $resultat_acteur = "";
                }
                //On affiche la boite de saisie de commentaire si l'utilisateur a cliqué sur le bouton "Commentez"
            if (empty($_POST['commentaire'])) {
                if ($donnees_acteur != $resultat_acteur) {                   
                    if (isset($_POST['comm_button'])) {
                        ?>
                        <p>
                       	<form id="commentaire" method="post" action="details_acteur.php">
                            <div  class="cke_comm">
                                <br/>
                                <fieldset class="field_comm"><legend>Votre commentaire :</legend>
                                <div>
                                    
                                </div>
                                <div>
                                <textarea class="comm_field" placeholder="Entrez votre commentaire ici..." name="commentaire" id="comm" rows="6" cols="50"></textarea><br/>
                                    <script>
                                        CKEDITOR.replace( 'comm', {
                                        uiColor: '#FF0000',
                                        toolbar: [
                                           [ 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink' ],
                                           [ 'FontSize', 'TextColor', 'BGColor' ]
                                        ]});
                                    </script>
                                </div>
                                <div>
                                    
                                </div>
                                </fieldset>
                            </div>
                            <div class="comm_send">
                                <input type="hidden" name="id_acteur" id="id_acteur" value="<?php echo $donnees['id_acteur']; ?>">
                                <input class="form_button" type="submit" name="Envoyer">
                            </div>
                       	</form>
                        </p>
                        <?php    
                    }
                    
                    } else { //Si le commentaire est déjà fait on affiche un message
                        ?>
                        <p style="color: green;">
                            <i>Vous avez laissé un commentaire sur cet acteur...</i>
                        </p>
                    <?php
                    }
                
            } else {  
                //On enregistre le commentaire dans la base de données                  
                $post = trim($_POST['commentaire']);
                $res = $bdd->prepare('INSERT INTO post (id_user, id_acteur, date_add, post) VALUES (:id_user, :id_acteur, NOW(), :post)');      
                $res->bindValue(':id_user',   $id_user, PDO::PARAM_INT);
                $res->bindValue(':id_acteur',   $donnees_acteur, PDO::PARAM_INT);
                $res->bindValue(':post',   $post, PDO::PARAM_STR);
                
                if ($res->execute()) {
                    unset($_POST['action']);
                    ?>
                    <form name="id_acteur" method="post" action="./details_acteur.php"> 
                    <input type="hidden" name="id_acteur" value="<?php echo $donnees['id_acteur']; ?>" /> 
                    </form> 
                    <script type="text/javascript"> document.id_acteur.submit(); //on envoie le formulaire vers details_acteur.php
                    </script>
                    <p style="color: green;">Commentaire ajouté!</p>
                    <?php
                }
            }                   
            ?>
        </div>
        <div class="groupe_com">
            <div class="header_comm">    
                    <div>
                        <?php //avant de lister les commentaires on affiche le compteur, le titre, le bouton "commentez" sous le titre puis le système de Like 
                        $req = $bdd->prepare('SELECT id_acteur FROM post WHERE id_acteur = ?');
                        $req->execute(array($donnees_acteur));
                        $req->rowCount();
                        $count_comm = $req->rowCount();
                        ?>
                        <span class="count_comm_box"><input class="count_comm" value="<?= $count_comm ?> commentaire(s)"/></span>                  
                        <?php
                        ?>
                    </div>
                    
                    <div class="vote_section">
                        <?php $vote = $bdd->prepare('SELECT id_acteur FROM vote WHERE id_user = :id_user');
                        $vote->execute(array('id_user' => $id_user));
                        $likedislike = $vote->fetch();
                        if (empty($likes)) {
                            $likes = 0;
                        }
                        if (empty($dislikes)) {
                            $dislikes = 0;
                        }
                        //On vérifie l'état des like pour l'acteur en cours
                        if(isset($id_acteur) AND !empty($id_acteur)) {
                            //$get_id = htmlspecialchars($_GET['id']);
                            $acteur = $bdd->prepare('SELECT * FROM acteur WHERE id_acteur = :id_acteur');
                            $acteur->bindValue(':id_acteur',   $id_acteur, PDO::PARAM_STR);
                            $acteur->execute();
                            $acteur = $acteur->fetch();
                            $id = $id_acteur;
                            $likes = $bdd->prepare('SELECT id_acteur FROM vote WHERE id_acteur = ? AND vote = 1');
                            $likes->execute(array($id));
                            $likes = $likes->rowCount();
                            $dislikes = $bdd->prepare('SELECT id_acteur FROM vote WHERE id_acteur = ? AND vote = 2');
                            $dislikes->execute(array($id));
                            $dislikes = $dislikes->rowCount();
                        } //On affiche les bouton et les compteurs de like / dislike
                        ?>
                    <div class="right_buttons">
                        <div class="txt_commentaires">
                        <form id="unhide_comm" method="post" action="details_acteur.php">
                            <input type="hidden" name="comm_button" id="comm_button" value="comm_button">
                            <input type="hidden" name="id_acteur" id="id_acteur" value="<?php echo $donnees['id_acteur']; ?>">
                            <input class="comm_button" type="submit" name="commentez" value="Nouveau Commentaire">
                        </form>           
                        </div>
                        <div class="vote">
                            <a href="./likedislike.php?t=1&id=<?= $id ?>"><img class="pouce_vert" src="./files/thumb_up.png" alt="pouce vert"></a> 
                            <span class="likedislike_box"><input class="likedislike_count" value="<?= $likes ?>"/></span>
                            <a href="./likedislike.php?t=2&id=<?= $id ?>"><img class="pouce_rouge" src="./files/thumb_down.png" alt="pouce rouge"></a> 
                            <span class="likedislike_box"><input class="likedislike_count" value="<?= $dislikes ?>"/></span>
                        </div>
                    </div>    
                    </div> 
                </div>
                <div>
                    <?php
                    // Récupération des commentaires
                    $req = $bdd->prepare('SELECT id_user, post, DATE_FORMAT(date_add, \'%d/%m/%Y à %Hh%imin%ss\') AS date_comm FROM post WHERE id_acteur = ? ORDER BY date_add');
                    $req->execute(array($donnees_acteur));
                    
                        while ($com_data = $req->fetch())
                        {
                        ?>
                        <div class="comm_flex">
                            <div>
                                
                            </div>
                            <div class="comm_individuel">
                            <?php //affichage des commentaires
                                $res = $bdd->prepare('SELECT prenom FROM account WHERE id_user = ?');
                                $res->execute(array($com_data['id_user']));
                                $com_username = $res->fetch();
                                ?>
                                <p><strong><?php echo $com_username['prenom']; ?></strong> <i><br/>le <?php echo $com_data['date_comm']; ?></i></p>
                                <p><?php echo $com_data['post']; ?></p>
                            </div>
                            <div>
                                
                            </div>
                        </div>
                        <?php
                        }
                        
                        // Fin de la boucle des commentaires
                        $req->closeCursor();
                    ?>
                </div>
        </div>
    <?php include('footer.php'); ?>
