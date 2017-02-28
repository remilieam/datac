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
        <title>DATÀC – Gérer les personnes</title>
        <meta charset = "utf-8" />
        <link rel = "stylesheet" href = "msf_interne.css"/>
		<link rel = "icon" type = "image/png" href = "../../images/datac_logo.png" />
    </head>
    <body>
        <br/><br/><br/><br/> 
        <header>
			<p class = "haut">Bienvenue sur DATÀC</p>
            <p class = "connexion"><a href="accueil_gestionnaire.php">Accueil</a> // Gérer les personnes</p>
            <p class = "deconnexion"><a href = "logout.php">Déconnexion</a></p>
        </header>
		<br/><br/><br/>
<?php 
        include("menu.php");
?>
		<section>
			<br/>
			<fieldset> 
				<legend>Gérer les personnes</legend> 
				<br/>
				En tant que gestionnaire, vous pouvez :
				<ul>
					<li><a href = "ajouter_gestionnaire.php">Ajouter une personne</a></li>
					<li><a href = "supprimer_gestionnaire.php">Supprimer une personne</a></li>
				</ul>
			</fieldset>
			<br/> 
		</section>
	</body>
</html>
<?php
}
?>
