<?php
session_start()
?>
<?php
include('cookies_accept.php');
?>
    <?php include('header.php'); ?>
    <?php
    //Ouuverture de la base de données et vérification des variables POST et message d'erreur si necessaire
    include('openbdd.php');
    $id_user = $_SESSION['id_user'];
    if(isset($_POST['change_name'])) {
        $name = htmlspecialchars($_POST['change_name']);
        if(empty($name)) { 
            ?>
            <div align="center">
                <p class="error_signup" align="center">Merci de renseigner votre nouveau nom...</p>
                <a href="mon_compte.php">Cliquez ici pour retourner à la gestion du compte</a>
            </div>
            <?php
        } elseif(!preg_match("#^[a-zA-Z]+$#",$name)) {
            ?>
            <div align="center">
                <p class="error_signup" align="center">Le nom ne peut contenir que des lettres majuscules et minuscules.</p>
                <a href="mon_compte.php">Cliquez ici pour retourner à la gestion du compte</a>
            </div>
            <?php
        } else { //si c'est bon on update le profil dans la base de données
            $res = $bdd->prepare('UPDATE account SET nom = :nom WHERE id_user = :id_user');
            $res->bindvalue(':id_user',   $id_user, PDO::PARAM_INT);
            $res->bindvalue(':nom',   $name, PDO::PARAM_STR);
            $res->execute()
            ?>
            <div align="center" class="modif_success">
                <strong><p>Votre nom a bien été modifié</p></strong> <br/>
                <a href="mon_compte.php">Cliquez ici pour retourner à la gestion du compte</a>
            </div>
            <?php
        }
    } elseif (isset($_POST['change_forname'])) {
        $forname = htmlspecialchars($_POST['change_forname']);
        if(empty($forname)) {
            ?>
            <div align="center">
                <p class="error_signup" align="center">Merci de renseigner votre nouveau prénom...</p>
                <a href="mon_compte.php">Cliquez ici pour retourner à la gestion du compte</a>
            </div>
            <?php
        } elseif(!preg_match("#^[a-zA-Z]+$#",$forname)) {
            ?>
            <div align="center">
                <p class="error_signup" align="center">Le nom ne peut contenir que des lettres majuscules et minuscules.</p>
                <a href="mon_compte.php">Cliquez ici pour retourner à la gestion du compte</a>
            </div>
            <?php
        } else { //si c'est bon on update le profil dans la base de données
            $res = $bdd->prepare('UPDATE account SET prenom = :prenom WHERE id_user=:id_user');
            $res->bindvalue(':id_user',   $id_user, PDO::PARAM_INT);
            $res->bindvalue(':prenom',   $forname, PDO::PARAM_STR);
            $res->execute();
            ?>
            <div align="center" class="modif_success">
                <strong><p>Votre prénom a bien été modifié</p></strong> <br/>
                <a href="mon_compte.php">Cliquez ici pour retourner à la gestion du compte</a>
            </div>
            <?php
        }
    } elseif (isset($_POST['change_username'])) { //Vérification des variable et de leur contenu et vérification des erreurs
        $username = $_POST['change_username'];
        if(empty($username)) {
            ?>
            <div align="center">
                <p class="error_signup" align="center">Merci de renseigner votre nom...</p>
                <a href="mon_compte.php">Cliquez ici pour retourner à la gestion du compte</a>
            </div>
            <?php
        } elseif(!preg_match("#^[a-zA-Z]+$#",$username)) {
            ?>
            <div align="center">
                <p class="error_signup" align="center">Le nom ne peut contenir que des lettres majuscules et minuscules.</p>
                <a href="mon_compte.php">Cliquez ici pour retourner à la gestion du compte</a>
            </div>
            <?php
        } elseif(!empty($username)) {
                $req = $bdd->query('SELECT username FROM account WHERE username="'.$username.'"');
                $chk_username = $req->fetch(PDO::FETCH_ASSOC);
                    if(!empty($_POST) && $chk_username == '1' || $chk_username > '1') {       
                    ?>
                    <div class="error_txt" align="center">
                        <p class="error_signup" align="center">Ce nom d'utilisateur est déjà pris...</p>
                        <a href="mon_compte.php">Cliquez ici pour retourner à la gestion du compte</a>
                    </div>
                    <?php
                } else { //si c'est bon on update le profil dans la base de données
                    $res = $bdd->prepare('UPDATE account SET username = :username WHERE id_user=:id_user');
                    $res->bindvalue(':id_user',   $id_user, PDO::PARAM_INT);
                    $res->bindvalue(':username',   $username, PDO::PARAM_STR);
                    $res->execute();
                    ?>
                    <div align="center" class="modif_success">
                        <strong><p>Votre nom d'utilisateur a bien été modifié</p></strong> <br/>
                        <a href="mon_compte.php">Cliquez ici pour retourner à la gestion du compte</a>
                    </div>
                    <?php
                }
        }
    } elseif (isset($_POST['password'], $_POST['password2'])) { //Vérification des variable et de leur contenu et vérification des erreurs
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
            if(empty($password)) {
                ?>
                <div align="center">
                    <p class="error_signup" align="center">Vous devez renseigner un mot de passe...</p>
                    <a href="mon_compte.php">Cliquez ici pour retourner à la gestion du compte</a>
                </div>
                <?php
            } elseif(!preg_match("#^(?=.{8,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#",$password)) {
                ?>
                <div align="center">
                    <p class="error_signup" align="center">Votre mot de passe ne doit contenir que des lettres majuscules, minuscules, des chiffres, des caractères spéciaux et doit être d'une longueur minimum de 8 caractères...</p>
                    <a href="mon_compte.php">Cliquez ici pour retourner à la gestion du compte</a>
                </div>
                <?php
            } elseif($password2 != $password) {
                ?>
                <div align="center">
                    <p class="error_signup" align="center">Les mots de passe ne correspondent pas...</p>
                    <a href="mon_compte.php">Cliquez ici pour retourner à la gestion du compte</a>
                </div>
                <?php
            } else { //si c'est bon on update le profil dans la base de données
                $password = password_hash($password, PASSWORD_DEFAULT); 
                $res = $bdd->prepare('UPDATE account SET password = :password WHERE id_user=:id_user');
                $res->bindvalue(':id_user',   $id_user, PDO::PARAM_INT);
                $res->bindValue(':password',   $password, PDO::PARAM_STR);
                $res->execute();
                ?>
                <div align="center" class="modif_success">
                    <strong><p>Votre mot de passe a bien été modifié</p></strong> <br/>
                    <a href="mon_compte.php">Cliquez ici pour retourner à la gestion du compte</a>
                </div>
                <?php
            }
    } elseif (isset($_POST['question'], $_POST['reponse'], $_POST['reponse2'])) { //Vérification des variable et de leur contenu et vérification des erreurs
                    $question = htmlspecialchars($_POST['question']);
                    $reponse = htmlspecialchars($_POST['reponse']);
                    $reponse2 = htmlspecialchars($_POST['reponse2']);
                if(!preg_match("#^[a-zA-Z0-9\s]+$#",$question)) {
                    ?>
                    <div align="center">
                        <p class="error_signup" align="center">Votre question ne doit contenir que des chiffres et des lettres...</p>
                        <a align="center" href="mon_compte.php">Cliquez ici pour retourner à la gestion du compte</a>
                    </div>
                    <?php
                } elseif(empty($question)) {
                    ?>
                    <div align="center">
                        <p class="error_signup" align="center">Merci de renseigner une question secrète...</p>
                        <a href="mon_compte.php">Cliquez ici pour retourner à la gestion du compte</a>
                    </div>
                    <?php
                } elseif(!preg_match("#^[a-zA-Z0-9\s]+$#",$reponse)) {
                    ?>
                    <div align="center">
                        <p class="error_signup" align="center">Votre question ne doit contenir que des chiffres et des lettres...</p>
                        <a href="mon_compte.php">Cliquez ici pour retourner à la gestion du compte</a>
                    </div>
                    <?php
                } elseif(empty($reponse)) {
                    ?>
                    <div align="center">
                        <p class="error_signup" align="center">Merci de renseigner la réponse à votre question secrète...</p>
                        <a href="mon_compte.php">Cliquez ici pour retourner à la gestion du compte</a>
                    </div>
                    <?php
                } elseif($reponse2 != $reponse) {
                    ?>
                    <div align="center">
                        <p class="error_signup" align="center">Les réponses ne correspondent pas...</p>
                        <a align="center" href="mon_compte.php">Cliquez ici pour retourner à la gestion du compte</a>
                    </div>
                    <?php
                } else { //si c'est bon on update le profil dans la base de données
                    $reponse = password_hash($reponse, PASSWORD_DEFAULT); 
                    $res = $bdd->prepare('UPDATE account SET question = :question, reponse = :reponse WHERE id_user=:id_user');
                    $res->bindvalue(':id_user',   $id_user, PDO::PARAM_INT);
                    $res->bindValue(':question',   $question, PDO::PARAM_STR);
                    $res->bindValue(':reponse',   $reponse, PDO::PARAM_STR);
                    $res->execute();
                    ?>
                    <div align="center" class="modif_success">
                        <strong><p>Votre question et votre réponse ont bien été modifiés</p></strong> <br/>
                        <a href="mon_compte.php">Cliquez ici pour retourner à la gestion du compte</a>
                    </div>
                    <?php
                }
    } else {
        if(isset($_POST['name_change'])) { //vérification de la variable et affichage du champs de modification
            ?>
            <div class="form_change">
                <div>
                    <!-- Pour Flex -->
                </div>
                <div class="change_fields">
                    <form id="change_name" action="" method="post">
                        <p align="center">
                        <label>Nouveau Nom :</label><br/>
                        <input class="fields" type="text" name="change_name" autofocus/><br/>
                        <input class="bouton_modifier" type="submit" value="Valider" /><br/>
                        </p>
                        <p align="center"><a href="">Retour</a></p>
                    </form>
                </div>
                <div>
                    <!-- Pour Flex -->    
                </div>
            </div>
            <?php
        } elseif (isset($_POST['forname_change'])) { //vérification de la variable et affichage du champs de modification
            ?>
            <div class="form_change">
                <div>
                </div>
                <div class="change_fields">
                    <form id="change_forname" action="" method="post">
                        <p align="center">
                        <label>Nouveau Prénom :</label><br/>
                        <input class="fields" type="text" name="change_forname" autofocus/><br/>
                        <input class="bouton_modifier" type="submit" value="Valider" /><br/>
                        </p>
                        <p align="center"><a href="">Retour</a></p>
                    </form>
                </div>
                <div>
                </div>
            </div>
            <?php
        } elseif (isset($_POST['username_change'])) { //vérification de la variable et affichage du champs de modification
            ?>
            <div class="form_change">
                <div>
                </div>
                <div class="change_fields">
                    <form id="change_username" action="" method="post">
                        <p align="center">
                        <label>Choisissez votre nouveau nom d'utilisateur :</label><br/>
                        <input class="fields" type="text" name="change_username" autofocus/><br/>
                        <input class="bouton_modifier" type="submit" value="Valider" /><br/>
                        </p>
                        <p align="center"><a href="">Retour</a></p>
                    </form>
                </div>
                <div>
                </div>
            </div>
            <?php
        } elseif (isset($_POST['password_change'])) { //vérification de la variable et affichage du champs de modification
            ?>
            <div class="form_change">
                <div>
                </div>
                <div class="change_fields">
                    <form id="change_password" action="" method="post">
                        <p align="center">
                        <label>Choisissez votre mot de passe :</label><br/>
                        <input class="fields" type="password" name="password" autofocus/><br/>
                        <label>Veuillez retaper votre mot de passe :</label><br/>
                        <input class="fields" type="password" name="password2" /><br/>
                        <input class="bouton_modifier" type="submit" value="Valider" /><br/>
                        </p>
                        <p align="center"><a href="">Retour</a></p>
                    </form>
                </div>
                <div>
                </div>
            </div>
            <?php
        } elseif (isset($_POST['question_change'])) { //vérification de la variable et affichage du champs de modification
            ?>
            <div class="form_change">
                <div>

                </div>
                <div class="change_fields">
                    <form id="change_question" action="" method="post">
                        <p align="center">
                        <label>Tapez votre question secrète en cas de perte de mot de passe :</label><br/>
                        <input class="fields" type="text" name="question" autofocus/><br/>
                        <label>Tapez la réponse à votre question secrète :</label><br/>
                        <input class="fields" type="password" name="reponse" /><br/>            
                        <label>Veuillez retaper la réponse à votre question :</label><br/>
                        <input class="fields" type="password" name="reponse2" /><br/>            
                        <input class="bouton_modifier" type="submit" value="Valider" /><br/>
                        </p>
                        <p align="center"><a href="">Retour</a></p>
                    </form>
                </div>
                <div>

                </div>
            </div>
            <?php
        } else { //Vérification de la connexion de l'utilisateur
            if (isset($_SESSION['id_user']) AND ($_SESSION['nom']) AND ($_SESSION['prenom']) AND ($_SESSION['username'])) { 
                    //Recherche des infos utilisateurs dans la base de données
        	    	$id_user = $_SESSION['id_user'];
        	        $req = $bdd->prepare('SELECT * FROM account WHERE id_user = ?');
        	        $req->execute(array($id_user));
                    $infos = $req->fetch();
                    //Affichage des infos et boutons de modification
                    ?>
                    <div class="user_title"> 
                            <strong><h3>Vos informations personnelles</h3></strong><br/>
                    </div>
                    <div class="user_page_center">
                    	<div>
                    		<!--  FLEX  -->
                    	</div>
                        <div class="user_details">
                  	        <strong>Nom :  </strong><?php echo $infos['nom']; ?>
                                <form class="user_info" id="name_change" action="" method="post">
                                <input type="hidden" name="name_change" id="name_change" value="name"> 
                                <em><input class="bouton_modifier" type="submit" name="modifier" value="Modifier"></em>
                                </form> <br/>
     			            <strong>Prénom :  </strong><?php echo $infos['prenom']; ?> 
                                <form class="user_info" id="forname_change" action="" method="post">
                                <input type="hidden" name="forname_change" id="forname_change" value="forname"> 
                                <em><input class="bouton_modifier" type="submit" name="modifier" value="Modifier"></em>
                                </form> <br/>
        		            <strong>Nom d'utilisateur :  </strong><?php echo $infos['username']; ?>
                                <form class="user_info" id="username_change" action="" method="post">
                                <input type="hidden" name="username_change" id="username_change" value="username"> 
                                <em><input class="bouton_modifier" type="submit" name="modifier" value="Modifier"></em>
                                </form> <br/>
                            <strong>Mot de passe :  </strong>******** <br/>
                                <form class="user_info" id="password_change" action="" method="post">
                                <input type="hidden" name="password_change" id="password_change" value="password">
                                <em><input class="bouton_modifier" type="submit" name="modifier" value="Modifier"></em>
                                </form> <br/>
        			        <strong>Question secrète :  </strong><?php echo $infos['question']; ?>
                                <form class="user_info" id="question_change" action="" method="post">
                                <input type="hidden" name="question_change" id="question_change" value="question">
                                <em><input class="bouton_modifier" type="submit" name="modifier" value="Modifier"></em>
                                </form> <br/>
        			        <strong>Réponse secrète :  </strong>******** <br/>
        		        </div>
        		        <div>
                    		<!--  FLEX  -->
                    	</div>
        	        </div>
                    <br/><p align="center"><a href="home.php">Cliquez ici pour retourner à la page d'accueil.</a></p>
                    <?php
                } else {
                    header('Location: index.php');
                }
            }
        }
    include('footer.php'); ?>
