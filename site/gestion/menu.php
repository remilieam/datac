<?php
	require("../connect.php");
	mysqli_set_charset($BDD, "utf8");
	$marequete = "SELECT * FROM personne WHERE id_pers = ".$_SESSION['idpers'];
	$monrs = mysqli_query($BDD, $marequete);
	$tuple = mysqli_fetch_array($monrs)['login'];
?>
		<br/><br/><br/>
		<nav> 
			Connecté (<?php echo $tuple; ?>)
			<br/>
			<a href = "form.php">Modifier mon compte</a>
		</nav>
		<section class = "menu">
			<table class = "menu_tab">
				<tr>
					<td><a href = "page_ajout.php">Ajouter</a></td>
					<td><a href = "page_suppression.php">Supprimer</a></td>
					<td><a href = "page_deplacement.php">Déplacer</a></td>
					<td><a href = "page_modification.php">Modifier</a></td>
					<td><a href = "page_copie.php">Dupliquer</a></td>
					<td><a href = "../accueil.php">Le site DATÀC</a></td>
<?php 
	if ($_SESSION['statut'] == "modérateur")
	{
?>
					<td><a href = "page_gestion.php">Les personnes</a></td>
<?php
	}
?>
				</tr>
			</table>
		</section>
		<br/>
