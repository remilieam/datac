<!doctype html>
<?php
session_start();
// mise en place d'une sécurité d'accès des pages
// il faut mettre le session_start sinon le empty vérifie que la variable n'exsite pas et
//  sans le session_strart la variable n'exsite pas et donc on est tout le temps dans le if
if (empty($_SESSION['idpers']))
{
	header('Location : page_non_connexion.php');
	exit();
}
else
{
?>
<html>
	<head>
		<title>DATÀC – Supprimer une personne</title>
		<meta charset = "utf-8" />
		<link rel = "stylesheet" href = "msf_interne.css"/>
		<link rel = "icon" type = "image/png" href = "../../images/datac_logo.png" />
	</head>
	<body>
		<br/><br/><br/><br/> 
		<header>
			<p class = "haut">Bienvenue sur DATÀC</p>
			<p class = "connexion"><a href = "accueil_gestionnaire.php">Accueil</a> // Supprimer une personne</p>
			<p class = "deconnexion"><a href = "logout.php">Déconnexion </a></p>
		</header>
		<br/><br/><br/>
<?php 
	include("menu.php");
?>
		<section>
			<br/>
			<fieldset>
				<legend>Supprimer une personne</legend>
<?php
	$MaRequete = " SELECT * FROM personne where statut = 'gestionnaire'";
	$MonRs = mysqli_query($BDD, $MaRequete);
	$suppr = false;
	
	// S'il y a bien des personnes à supprimer 
	if(mysqli_num_rows($MonRs) != 0)
	{
?>
                Veuillez choisir la personne que vous souhaitez supprimer ?
				<br/><br/>
				<p class = "barre">
					<form method = "POST">
						Personne à supprimer :
						<select name = "idpers">
							<option selected value = 0>--- Choix de la personne ---</option>
<?php
		while ($tuple = mysqli_fetch_array($MonRs))
		{
?>
							<option value = "<?php echo $tuple['id_pers']; ?>"><?php echo $tuple['Prenom']." ".$tuple['Nom']; ?></option>
<?php        
		}
?>
						</select>
						<br/><br/>
						<p class = "validation" >
							<input type = "submit"  name = "btn_envoi" id = "btn_envoi" value = "Supprimer"	/>
							<input type = "reset" value = "Annuler" />
						</p>
						<br/><br/>
					</form>
				</p>
<?php
	}
	
	// S'il n'y a personne à supprimer
	else
	{
?>
				<p>Il n’y a pas de suppression possible pour l’instant...</p>
				<a href = "accueil_gestionnaire.php">Retour à l’accueil</a>
<?php
	}
?>
			</fieldset>
			<br/>
		</section>
	</body>
</html>
<?php
    if(isset($_POST["btn_envoi"]))
	{
		$MaRequete = "delete from `personne` where id_pers = ".$_POST['idpers'];
		
		if(mysqli_query($BDD, $MaRequete))
		{
            // Message d'alerte
?>
            <script>
				alert("<?php echo htmlspecialchars('Votre personne a bien été supprimée.', ENT_QUOTES); ?>")
				window.location.href = 'accueil_gestionnaire.php';
			</script>
<?php
		}
	}
}
?>
