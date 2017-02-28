<?php
session_start();
// mise en place d'une sécurité d'accès des pages
// il faut mettre le session_start sinon le empty vérifie que la variable n'exsite pas et
//  sans le session_strart la variable n'exsite pas et donc on est tout le temps dans le if
if (empty($_SESSION['idpers']))
{
	header('Location: page_non_connexion.php');
	exit();
}
else
{
?>
<!doctype html>
<html>
    <head>
        <title>DATÀC – Modification</title>
        <meta charset = "utf-8" />
        <link rel = "stylesheet" href = "msf_interne.css"/>
		<link rel = "icon" type = "image/png" href = "../../images/datac_logo.png" />
    </head>
    <body>
        <br/><br/><br/><br/> 
        <header>
			<p class = "haut">Bienvenue sur DATÀC</p>
            <p class = "connexion"><a href="accueil_gestionnaire.php">Accueil</a> // Modifier</p>
            <p class = "deconnexion"><a href = "logout.php">Déconnexion</a></p>
        </header>
		<br/><br/><br/>
<?php 
        include("menu.php");
?>
		<section>
			<br/>
			<fieldset> 
				<legend>Faire des modifications</legend> 
				<br/>
				Dans cette catégorie, vous pouvez :
				<ul>
					<li><a href = "modifier_dispositif.php">Modifier un dispositif</a></li>
					<li><a href = "modifier_categorie.php">Modifier une catégorie</a></li>
					<li><a href = "modifier_deficience.php">Modifier une déficience</a></li>
				</ul>
			</fieldset>
			<br/> 
		</section>
	</body>
</html>
<?php
}
?>