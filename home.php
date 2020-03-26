<?php
include('header.php'); 
include('cookies_accept.php');
include('timeout.php');
if(isset($_SESSION['id_user'])) {
    if(isLoginSessionExpired()) {
        header("Location:disconnect.php?session_expired=1");
    }
}
var_dump($_SESSION);
echo time();

            //On vérifie la connexion de l'utilisateur
            if (isset($_SESSION['id_user']) AND ($_SESSION['nom']) AND ($_SESSION['prenom']) AND ($_SESSION['username'])) 
            {
            ?>
            <!--   Présentation du site, des banques et des partenaires   -->
            <div class="txt_presentation">
                <h1 align="center">Bienvenue!</h1>
                <p>
                    Le Groupement Banque Assurance Français (GBAF) est une fédération<br/>
                    représentant les 6 grands groupes français.<br/>
                </p>
                <div class="logo_banques" align="center">
                    <a href="https://group.bnpparibas/"><img class="logo_bnp" src="./files/bnp.png" alt="logo bnp"></a>
                    <a href="https://groupebpce.com/"><img class="logo_bpce" src="./files/bpce.png" alt="logo bpce"></a>
                    <a href="https://www.credit-agricole.com/"><img class="logo_ca" src="./files/ca.png" alt="logo ca"></a>
                    <a href="https://www.cic.fr/fr/banques/institutionnel/index.html"><img class="logo_cic" src="./files/cic.png" alt="logo cic"></a>
                    <a href="https://www.creditmutuel.fr/fr/groupe/banque-solide.html"><img class="logo_cm" src="./files/cm.png" alt="logo cm"></a>
                    <a href="https://www.societegenerale.com/fr/"><img class="logo_sg" src="./files/sg.png" alt="logo sg"></a>
                    <a href="https://www.labanquepostale.com/"><img class="logo_bp" src="./files/bp.png" alt="logo bp"></a>
                </div>
                <p>
                    Même s’il existe une forte concurrence entre ces entités, elles vont toutes travailler<br/>
                    de la même façon pour gérer près de 80 millions de comptes sur le territoire<br/>
                    national.<br/>
                    Le GBAF est le représentant de la profession bancaire et des assureurs sur tous<br/>
                    les axes de la réglementation financière française. Sa mission est de promouvoir<br/>
                    l'activité bancaire à l’échelle nationale. C’est aussi un interlocuteur privilégié des<br/>
                    pouvoirs publics.<br/>
                </p>
            </div>
            <div class="txt_pres_acteur">
                <h2 align="center">Partenaires et Acteurs bancaires</h2>
                <p>
                    Voici la liste des partenaires et acteurs bancaires sur lesquels vous pouvez laisser un avis ou un commentaires.<br/>
                    Il s'agit d'une brève description de chacun mais vous pouvez en savoir plus en cliquant sur "lire la suite"<br/>
                    situé à droite du partenaire ou acteur concerné.<br/>
                </p>
            </div>
            <?php
            // Connexion à la base de données
            include('openbdd.php');

            // On récupère les acteurs bancaires
            $req = $bdd->query('SELECT id_acteur, acteur, description, logo FROM acteur ORDER BY id_acteur');
            ?>
            <div class="acteurs">
            <?php
            while ($donnees = $req->fetch())
            {
            ?>
            <div class="acteur">
                <!--   On liste les acteur avec une partie de leur description accompagnés de leur logo et d'un bouton pour accéder au détails   -->
                <img align="center" class="logo_acteur_img" src="<?php echo $donnees['logo']; ?>">
                
                <div class="texte_acteur">
                    <div>
                        <h3><?php echo $donnees['acteur']; ?></h3>
                        <p>
                        <?php
                        // On affiche le contenu de la description
                        $description = ($donnees['description']);
                        $short_description = substr($description, 0, 210);
                        echo $short_description; echo "...";
                        ?>
                        </p>
                    </div>
                    <div>
                        <div class="bouton_lire_suite">
                            <form id="page_acteur" action="details_acteur.php" method="post">  
                                <input type="hidden" name="id_acteur" id="id_acteur" value="<?= $donnees['id_acteur']; ?>">
                                <em><input class="form_button" type="submit" name="Détails" value="Lire la suite"></em>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
            </div>
            <?php
                // Fin de la boucle des acteurs
                $req->closeCursor();
            ?>
            <?php
            }
            else // Si on est pas connecté on affiche un message pour dire qu'il faut l'être
            {
                header('Location: index.php');
            }
    include('footer.php'); ?>
    
