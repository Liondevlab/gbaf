<?php
include('header.php');
include('cookies_accept.php');
include('timeout.php');
if(isset($_SESSION['id_user'])) {
    if(isLoginSessionExpired()) {
        header("Location:disconnect.php?session_expired=1");
    }
}   
?>
        <div class="txt_loi">
            
                <!--   Texte d'infos sur les cookies de la CNIL   -->

            <p>
                <h3>Cette déclaration sur les cookies a été mise à jour pour la dernière fois le 16 Mars 2020 et s’applique aux citoyens de l’Union européenne.</h3> <br/>
                <h3>1. Introduction</h3>
                Notre site web, https://gbaf.liondevlab.com (ci-après : « le site web ») utilise des cookies et autres technologies liées (par simplification, toutes ces technologies sont désignées par le terme « cookies »). <br/> 
                Des cookies sont également placés par des tiers que nous avons engagés. Dans le document ci-dessous, nous vous informons de l’utilisation des cookies sur notre site web.
                <h3>2. Qu’est-ce qu’un cookie ?</h3>
                Un cookie est un petit fichier simple envoyé avec les pages de ce site web et stocké par votre navigateur sur le disque dur de votre ordinateur ou d’un autre appareil. <br/>
                 Les informations qui y sont stockées peuvent être renvoyées à nos serveurs ou aux serveurs des tiers concernés lors d’une visite ultérieure.
                <h3>3. Qu’est-ce qu’un script ?</h3>
                Un script est un bout de code de programme utilisé afin de faire fonctionner notre site web correctement et de manière interactive. <br/>
                Ce code est exécuté sur notre serveur ou votre appareil.
                <h3>3. Qu’est-ce qu’un pixel espion ?</h3>
                Un pixel espion (ou balise web) est un petit morceau de texte ou d’image invisible sur un site web, utilisé pour suivre le trafic sur un site web. <br/>
                Pour ce faire, diverses données vous concernant sont stockées à l’aide de pixels espions.
                <h3>5. Consentement</h3>
                Lorsque vous visitez notre site web pour la première fois, nous vous afficherons une fenêtre contextuelle avec une explication sur les cookies. <br/>
                Dès que vous cliquez sur « Tous les cookies », vous consentez à notre utilisation de tous les cookies et extensions, comme décrit dans la fenêtre contextuelle et cette déclaration sur les cookies. <br/>
                Vous pouvez désactiver l’utilisation de cookies sur votre navigateur, mais veuillez noter que notre site web risque de ne plus fonctionner correctement.
                <h3>6. Cookies</h3>
                <h4>6.1 Cookies techniques ou fonctionnels</h4>
                Certains cookies assurent le fonctionnement correct de certaines parties du site web et la prise en compte de vos préférences en tant qu’utilisateur. <br/>
                En plaçant des cookies fonctionnels, nous vous facilitons la visite de notre site web. Ainsi, vous n’avez pas besoin de saisir à plusieurs reprises les mêmes informations lors de la visite de notre site web. <br/>
                Nous pouvons placer ces cookies sans votre consentement.
                <h4>6.2 Cookies analytiques</h4>
                Les statistiques sont suivies de façon anonyme, aucune permission n’est donc demandée pour placer des cookies analytiques.
                <h4>6.3 Cookies publicitaires</h4>
                Nous n’utilisons pas de cookies publicitaires sur ce site.
                <h4>6.4 Boutons de réseaux sociaux</h4>
                Nous n’utilisons pas de cookies de réseaux sociaux sur ce site.
                <h3>7. Cookies placés</h3>
                Nom Conservation    Fonction
                Données personnelles        
                username    1 an    Conserve votre nom d’utilisateur pour les futur connexions
                Partage
                Ces données ne sont pas partagées avec des tiers.
                <h3>8. Vos droits concernant les données personnelles</h3>
                Vous avez les droits suivants concernant vos données personnelles : <br/>
                <ul>
                <li>  Vous avez le droit de savoir pourquoi vos données personnelles sont nécessaires, ce qui leur arrivera, et combien de temps elles seront conservées. </li>
                <li>  Droit d'accès : vous avez le droit d’accéder à vos données personnelles dont nous avons connaissance. </li>
                <li>  Droit de rectification : vous avez le droit de compléter, corriger, faire supprimer ou bloquer vos données personnelles à tout moment. </li>
                <li>  Si vous nous donnez votre consentement pour le traitement de vos données, vous avez le droit de révoquer ce consentement et de faire supprimer vos données personnelles. </li>
                <li>  Droit de transférer vos données : vous avez le droit de demander toutes vos données personnelles au responsable et de les transférer dans leur intégralité à un autre responsable. </li>
                <li>  Droit d’opposition : vous pouvez vous opposer au traitement de vos données. Nous obtempérerons, à moins que certaines raisons ne justifient ce traitement. </li>
                </ul>
                Pour exercer ces droits, veuillez nous contacter. Veuillez vous référer aux coordonnées en bas de cette déclaration sur les cookies. <br/>
                Si vous avez une réclamation concernant la façon dont nous gérons vos données, nous aimerions en être informés, mais vous avez également le droit d’envoyer une réclamation aux autorités de contrôle <br/>
                (les autorités chargées de la protection des données, comme le CEPD).
                <h3>9. Activer/désactiver et supprimer les cookies</h3>
                Vous pouvez utiliser votre navigateur Internet pour supprimer automatiquement ou manuellement les cookies. Vous pouvez également spécifier que certains cookies ne peuvent pas être placés. <br/> 
                Une autre option consiste à modifier les paramètres de votre navigateur Internet afin que vous receviez un message à chaque fois qu'un cookie est placé. <br/>
                Pour plus d'informations sur ces options, reportez-vous aux instructions de la section Aide de votre navigateur.
                Veuillez noter que notre site web peut ne pas marcher correctement si tous les cookies sont désactivés. <br/>
                Si vous supprimez les cookies dans votre navigateur, ils seront de nouveau placés après votre consentement lorsque vous revisiterez nos sites web.
                <h3>10. Coordonnées</h3>
                Pour toute question et/ou tout commentaire sur notre politique de cookies et cette déclaration, veuillez nous contacter en utilisant les coordonnées suivantes : <br/>
                GBAF <br/>
                27 rue du Général de Gaulles <br/>
                93390 Clichy sous bois <br/>
                France <br/>
                Site web : https://gbaf.liondevlab.com <br/>
                E-mail : Contact@gbaf.fr <br/>
                Numéro de téléphone: 06 07 08 09 10 <br/>
            </p>
        </div>
    <?php include('footer.php'); ?>
