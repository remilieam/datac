<!doctype html>
<?php
session_start();
// mise en place d'une sécurité d'accès des pages
// il faut mettre le session_start sinon le empty vérifie que la variable n'exsite pas et
// sans le session_strart la variable n'exsite pas et donc on est tout le temps dans le if
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
		<title>DATÀC – Modifier une déficience</title>
		<meta charset = "utf-8" />
		<link rel = "stylesheet" href = "msf_interne.css"/>
		<link rel = "icon" type = "image/png" href = "../../images/datac_logo.png" />
	</head>
	<body>
		<br/><br/><br/><br/> 
		<header>
			<p class = "haut">Bienvenue sur DATÀC</p>
			<p class = "connexion"><a href = "accueil_gestionnaire.php">Accueil</a> // Modifier une déficience</p>
			<p class = "deconnexion"><a href = "logout.php">Déconnexion </a></p>
		</header>
		<br/><br/><br/>
<?php 
	include("menu.php");
?>
		<section>
			<br/>
			<fieldset>
				<legend>Modifier une déficience</legend> 
<?php
	// si le formulaire n'a pas déjà été soumis, on lui demande de choisir sa catégorie
	if (empty($_POST["id_def"]) || $_POST["id_def"] == -1)
	{
?>
				<p>Veuillez choisir la déficience que vous souhaitez modifier :</p>
				<form method = "POST">
					<select name = "id_def">
						<option value = -1>--- Choissisez une déficience ---</option>
<?php
		$marequete = "SELECT * FROM deficience ";
		$monrs = mysqli_query($BDD, $marequete);
		
		while ($tuple = mysqli_fetch_array($monrs))
		{
?>
					<option value = "<?php echo $tuple['id_deficience']; ?>"><?php echo $tuple['nom_def']; ?></option>
<?php
		}
		
		mysqli_free_result($monrs);
?>
					</select>
					<br/><br/>
					<p class = "validation" >
						<input type = "submit" name = "btn_envoi" id = "btn_envoi" value = "Suivant"/>
						<input type = "reset" value = "Annuler" />
					</p>
					<br/>
				</form>
<?php
	}
	
	// deuxième fois sur la page
	else 
	{
?>
				<p>Veuillez compléter les champs que vous souhaitez modifier. Vous n'êtes pas obligé de compléter tous les champs.</p> 
<?php
		// affichage des données actuelles sur le module
		$marequete = "SELECT * FROM deficience where id_deficience = ".$_POST['id_def'];
		$MOnrs = mysqli_query($BDD, $marequete);
?>
				<form method = "POST">
					<br/><br/>
					<table class = "tab2">
						<tr class = "nom_colonne">
							<td>Nom des colonnes dans la base</td>
							<td>Valeurs actuelles</td>
							<td>Vos modifications</td>
						</tr>
<?php
		while ($tuple = mysqli_fetch_array($MOnrs)) 
		{
?>
						<tr>
							<td>Nom de la déficience</td>
							<td><?php echo $tuple['nom_def']; ?></td>
							<td><input type = "text" name = "nom" id = "nom" size = "25" value = "<?php echo $tuple['nom_def']; ?>" required /></td>
						</tr>
						<tr>
							<td>Description de la déficience</td>
							<td><?php echo $tuple['texte_def']; ?></td>
							<td><textarea name = "descr" rows = "8" cols = "40" value = "<?php echo $tuple['texte_def']; ?>" required ><?php echo $tuple['texte_def']; ?></textarea></td>
						</tr>
<?php
		}
?>
					</table>
					<p class = "validation" >
						<input type = "hidden" name = "def" value = "<?php echo $_POST["id_def"]; ?>"/>
						<input type = "submit" name = "btn_envoi2" id = "btn_envoi2" value = "Modifier"/>
						<input type = "reset" value = "Annuler" />
					</p>
				</form>
<?php
	}
?>
			</fieldset>
			<br/>
		</section>
	</body>
</html>
<?php
	$modif = false;
	
	if(isset($_POST["btn_envoi2"]))
	{
		$mareq= "UPDATE `deficience` SET `nom_def` = '".$_POST['nom']."', `texte_def` = '".$_POST['descr']."' WHERE id_deficience = ".$_POST['def'];
		
		if(mysqli_query($BDD, $mareq))
		{
			// Message d'alerte
?>
			<script>
				alert("<?php echo htmlspecialchars('Votre modification a bien été prise en compte.', ENT_QUOTES); ?>")
				window.location.href='accueil_gestionnaire.php';
			</script>
<?php
		}
	}
}
?>