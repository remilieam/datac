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
		<title>DATÀC – Modifier une catégorie</title>
		<meta charset = "utf-8" />
		<link rel = "stylesheet" href = "msf_interne.css"/>
		<link rel = "icon" type = "image/png" href = "../../images/datac_logo.png" />
	</head>
	<body>
		<br/><br/><br/><br/> 
		<header>
			<p class = "haut">Bienvenue sur DATÀC</p>
			<p class = "connexion"><a href = "accueil_gestionnaire.php">Accueil</a> // Modifier une catégorie</p>
			<p class = "deconnexion"><a href = "logout.php">Déconnexion </a></p>
		</header>
		<br/><br/><br/>
<?php 
	include("menu.php");
?>
		<section>
			<br/>
			<fieldset>
				<legend>Modifier une catégorie</legend>
<?php 
	if (!isset($_POST["def"]) || $_POST["def"] == 0)
	{
?>
				<p>Veuillez choisir la déficience qui contient la catégorie que vous voulez modifier :</p>
				<form method = "POST">Nom de la déficience :
					<select id = "def" name = "def">
						<option value = 0>--- Choix de la déficience ---</option>
<?php
		$marequete = "SELECT * FROM deficience ";
		$monrs = mysqli_query($BDD, $marequete);
		
		while ($tuple = mysqli_fetch_array($monrs))
		{
?>
						<option value = "<?php echo $tuple['id_deficience']; ?>"><?php echo $tuple['nom_def']; ?></option>
<?php
		}
?>
					</select>
					<br/>
					<p class = "validation"> 
						<input type = "submit" name = "btn_envoi" id = "btn_envoi" value = "Suivant"/>
						<input type = "reset" value = "Annuler" />
					</p>
				</form> 
<?php
	}
	
	else if(!isset($_POST["idcat"]) || $_POST["idcat"] == 0) 
	{
		$marequete = "SELECT * FROM deficience where id_deficience = ".$_POST['def'];
		$monrs = mysqli_query($BDD, $marequete);
		$tuple = mysqli_fetch_array($monrs);
?>
				<p>Vous avez choisi : <?php echo $tuple['nom_def']; ?></p>
				<p>Veuillez choisir la catégorie que vous voulez modifier :</p>
				<form method = "POST">Nom de la catégorie :
					<select id = "idcat" name = "idcat">
						<option value = 0>--- Choix de la catégorie ---</option>
<?php
		$marequete = "SELECT * FROM categorie where id_def_cat = ".$_POST['def'];
		$monrs = mysqli_query($BDD, $marequete);
		
		while ($tuple = mysqli_fetch_array($monrs))
		{
?>
						<option value = "<?php echo $tuple['id_categorie']; ?>"><?php echo $tuple['nom_cat']; ?></option>
<?php
		}
?>
					</select>
					<br/>
					<p class = "validation" >
						<input type = "hidden" name = "def" value = <?php echo $_POST['def']; ?> />
						<input type = "submit" name = "btn_envoi" id = "btn_envoi" value = "Suivant"/>
						<input type = "reset" value = "Annuler" />
					</p>
				</form>
<?php
	}
	
	else
	{
?>
				<p>Veuillez compléter les champs que vous souhaitez modifier. Vous n'êtes pas obligé de compléter tous les champs.</p>
<?php
		$marequete = "SELECT * FROM categorie where id_categorie = ".$_POST['idcat'];
		$MOnrs = mysqli_query($BDD, $marequete);
		$tuple = mysqli_fetch_array($MOnrs);
?>
				<form method = "POST">
					<table class = "tab2">
						<tr class = "nom_colonne">
							<td>Nom des colonnes dans la base</td>
							<td>Valeurs actuelles</td>
							<td>Vos modifications</td>
						</tr>
						<tr>
							<td>Nom de la catégorie</td>
							<td><?php echo $tuple['nom_cat']; ?></td>
							<td><input type = "text" name = "nom" id = "nom" size = "25" value = "<?php echo $tuple['nom_cat']; ?>" required /></td>
						</tr>
						<tr>
							<td>Description de la catégorie</td>
							<td><?php echo $tuple['texte_cat']; ?></td>
							<td> <textarea name = "descr" rows = "8" cols = "40" value = "<?php echo $tuple['texte_cat']; ?>"><?php echo $tuple['texte_cat']; ?></textarea></td>
						</tr>
					</table>
					<br/>
					<p class = "validation" >
						<input type = "hidden" name = "idcat" value = <?php echo $_POST['idcat']; ?> />
						<input type = "submit" name = "modifier" id = "btn_envoi" value = "Modifier"/>
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
	
	if(isset($_POST["modifier"]))
	{
		$mareq= "UPDATE `categorie` SET `nom_cat` = '".$_POST['nom']."', `texte_cat` = '".$_POST['descr']."' WHERE id_categorie = ".$_POST['idcat'];
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