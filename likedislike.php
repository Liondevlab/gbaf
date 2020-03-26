<?php
session_start()
?>
<!DOCTYPE html> 
<html> 
   <head> 
   </head> 
   <body>
      <?php
      include('openbdd.php');
         //On vérifie que les données de vote sont bien présentes sinon on renvoi à l'accueil
         if(isset($_GET['t'],$_GET['id']) AND !empty($_GET['t']) AND !empty($_GET['id'])) {
            //On prépare les variables et on vérifie l'acteur dans la base de donnée
            $getid = (int) $_GET['id'];
            $gett = (int) $_GET['t'];
            $sessionid = $_SESSION['id_user'];
            $check = $bdd->prepare('SELECT COUNT(*) FROM acteur WHERE id_acteur = :id_acteur');
            $check->bindValue(':id_acteur',   $getid, PDO::PARAM_INT);
            $check->execute();
            
            if($getid != 0) { //La variable doit être différente de 0
               if($gett == 1) { //Si la valeur de $gett est à 1 alors il s'agit d'un pouce vert... alors on vérifie la base de donnée. si déja existant on l'enlève sinon on l'ajoute
                  
                  $check_like = $bdd->prepare('SELECT id_acteur FROM vote WHERE id_acteur = ? AND id_user = ?');
                  $check_like->execute(array($getid,$sessionid));
                  $del = $bdd->prepare('DELETE FROM vote WHERE id_acteur = ? AND id_user = ? AND vote = 2');
                  $del->execute(array($getid,$sessionid));
                  
                  if($check_like->rowCount() == 1) {
                     $del = $bdd->prepare('DELETE FROM vote WHERE id_acteur = ? AND id_user = ? AND vote = 1');
                     $del->execute(array($getid,$sessionid));
                  } else {
                     $ins = $bdd->prepare('INSERT INTO vote (id_acteur, id_user, vote) VALUES (?, ?, 1)');
                     $ins->execute(array($getid, $sessionid));
                  }
                  
               } elseif($gett == 2) { //Si la valeur de $gett est à 2 alors il s'agit d'un pouce rouge... alors on vérifie la base de donnée. si déja existant on l'enlève sinon on l'ajoute
                  
                  $check_like = $bdd->prepare('SELECT id_acteur FROM vote WHERE id_acteur = ? AND id_user = ?');
                  $check_like->execute(array($getid,$sessionid));
                  $del = $bdd->prepare('DELETE FROM vote WHERE id_acteur = ? AND id_user = ? AND vote = 1');
                  $del->execute(array($getid,$sessionid));
                  
                  if($check_like->rowCount() == 1) {
                     $del = $bdd->prepare('DELETE FROM vote WHERE id_acteur = ? AND id_user = ? AND vote =2');
                     $del->execute(array($getid,$sessionid));
                  } else {
                     $ins = $bdd->prepare('INSERT INTO vote (id_acteur, id_user, vote) VALUES (?, ?, 2)');
                     $ins->execute(array($getid, $sessionid));
                  }
               }
               ?>
               <form name="form1" method="post" action="./details_acteur.php"> 
                  <input type="hidden" name="id_acteur" value="<?php echo $getid; ?>" /> 
               </form> 
               <script type="text/javascript"> document.form1.submit(); //on envoie le formulaire vers details_acteur.php
               </script> 
               <?php
            } else {      
               exit('Erreur fatale. <a href="./home.php">Revenir à l\'accueil</a>');
            }
         } else {
            exit('Erreur fatale. <a href="./home.php">Revenir à l\'accueil</a>');
         }
         ?> 
   </body> 
</html>
