<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>DATÀC – Accueil</title>
		<link rel =" stylesheet" href ="mise_en_forme.css"/>
		<link rel="icon" type="image/png" href="../images/datac_logo.png" />
	</head>
	<body>
		<header>
			<h1>Dispositifs et aides techniques à la communication</h1> 
<?php include("bouton_recherche.php"); ?> 
<?php include("bouton_connexion.php"); ?> 
		</header>
		<section> 
			<article class="presentation">
				<h3>Bienvenue</h3>
				<p>
					Ce site présente un catalogue exhaustif des dispositifs et aides techniques à la communication, 
					destiné à toutes personnes en situation de handicap, leurs proches ainsi que les professionnels de santé.
				</p>
			</article>
			<article>
<?php
	// Connexion à la base de données
	require("connect.php");
	mysqli_set_charset($BDD, "utf8");
	// Recherche et affichage (hyperlien) de toutes les déficiences
	$RqtDef = "SELECT * FROM deficience";
	$TabDef = mysqli_query($BDD, $RqtDef);
	
	while ($LgnDef = mysqli_fetch_array($TabDef))
	{
?>
				<fieldset>
					<legend>
						<h3> 
							<a href="deficience.php?idDef=<?php echo $LgnDef["id_deficience"]; ?>"><?php echo $LgnDef["nom_def"]; ?></a> 
						</h3>
					</legend>
<?php
		// Affichage de la description de la déficience s’il y en a une
		if ($LgnDef["texte_def"] != NULL OR $LgnDef["texte_def"] != "") 
		{
?>
					<p class="descrp"><?php echo $LgnDef["texte_def"]; ?></p>
<?php
		}
?>
				</fieldset><br/>
<?php
	}
	
	mysqli_free_result($TabDef);
	mysqli_close($BDD);
?>
			</article>
		</section>
<?php include("bas.php"); ?> 
	</body>
</html>