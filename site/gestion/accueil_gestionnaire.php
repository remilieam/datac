<!doctype html>
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
<html>
	<head>
		<title>DATÀC – Gestion du site</title>
		<meta charset = "utf-8" />
		<link rel = "stylesheet" href = "msf_interne.css"/>
		<link rel = "icon" type = "image/png" href = "../../images/datac_logo.png" />
	</head>
	<body>
		<br/><br/><br/><br/> 
		<header>
			<p class = "haut">Bienvenue sur DATÀC</p>
			<p class = "connexion">Accueil</p>
			<p class = "deconnexion"><a href = "logout.php">Déconnexion </a></p>
		</header>
		<br/><br/><br/>
<?php 
	include("menu.php");
	
	// une partie qui ne s'affiche que si la personne a tous les pouvoirs
	if ($_SESSION['statut'] == "modérateur")
	{
?>
		<section>
			<br/>
			<fieldset> 
				<legend><a href = "page_gestion.php">Gérer les personnes</a></legend> 
				<br/>
				Dans cette catégorie, vous pouvez :
				<ul>
					<li><a href = "ajouter_gestionnaire.php">Ajouter une personne</a></li>
					<li><a href = "supprimer_gestionnaire.php">Supprimer une personne</a></li>
				</ul>
			</fieldset>
			<br/> 
		</section>
		<br/><br/><br/>
<?php
	}
?>
		<section>
			<br/>
			<fieldset> 
				<legend><a href = "page_ajout.php">Faire des ajouts</a></legend> 
				<br/>
				Dans cette catégorie, vous pouvez :
				<ul>
					<li><a href = "ajouter_dispositif.php">Ajouter un dispositif</a></li>
					<li><a href = "ajouter_categorie.php">Ajouter une catégorie</a></li>
					<li><a href = "ajouter_deficience.php">Ajouter une déficience</a></li>
				</ul>
			</fieldset>
			<br/> 
		</section>
		<br/><br/><br/>
		<section>
			<br/>
			<fieldset> 
				<legend><a href = "page_suppression.php">Faire des suppressions</a></legend> 
				<br/>
				Dans cette catégorie, vous pouvez :
				<ul>
					<li><a href = "supprimer_dispositif.php">Supprimer un dispositif</a></li>
					<li><a href = "supprimer_categorie.php">Supprimer une catégorie</a></li>
				</ul>
			</fieldset>
			<br/> 
		</section>
		<br/><br/><br/>
		<section>
			<br/>
			<fieldset> 
				<legend><a href = "page_modification.php">Faire des modifications</a></legend> 
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
		<br/><br/><br/>
		<section>
			<br/>
			<fieldset> 
				<legend><a href = "page_deplacement.php">Faire des déplacements</a></legend> 
				<br/>
				Dans cette catégorie, vous pouvez :
				<ul>
					<li><a href = "deplacer_dispositif.php">Déplacer un dispositif</a></li>
					<li><a href = "deplacer_categorie.php">Déplacer une catégorie</a></li>
				</ul>
			</fieldset>
			<br/> 
		</section>
		<br/><br/><br/>
		<section>
			<br/>
			<fieldset> 
				<legend><a href = "page_copie.php">Faire des duplications</a></legend> 
				<br/>
				Dans cette catégorie, vous pouvez :
				<ul>
					<li><a href = "copier_dispositif.php">Dupliquer un dispositif</a></li>
				</ul>
			</fieldset>
			<br/> 
		</section>
	</body>
</html>
<?php
}
?>