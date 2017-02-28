<!DOCTYPE html>
<?php
	// Connexion à la base de données
	require("connect.php");
	mysqli_set_charset($BDD, "utf8");

	// Recherche de l’indentifiant et du nom de la déficience sur laquelle on vient de cliquer
	$idDef = $_GET["idDef"];
	$RqtDef = "SELECT * FROM deficience WHERE id_deficience = $idDef";
	$TabDef = mysqli_query($BDD,$RqtDef);
	$LgnDef = mysqli_fetch_array($TabDef);
	$nomDef = $LgnDef["nom_def"];
	mysqli_free_result($TabDef);
?>

<html>
	<head>
		<meta charset="UTF-8">
		<title>DATÀC – <?php print $nomDef; ?></title>
		<link rel =" stylesheet" href ="mise_en_forme.css"/>
		<link rel="icon" type="image/png" href="../images/datac_logo.png" />
	</head>
	<body>
		<header>
			<h1>Dispositifs et aides techniques à la communication</h1>
			<!--Fil d’Ariane-->
			<p class="ariane">
				<a href="accueil.php">Accueil</a> 
				// <?php echo $nomDef; ?> 
			</p>
<?php include("bouton_recherche.php"); ?> 
<?php include("bouton_connexion.php"); ?> 
		</header>
		<section> 
			<article>
				<p>Pour cette déficience, il y a les catégories de dispositifs suivantes :</p>
				<ul>
<?php
	// Recherche et affichage (hyperliens) de toutes les catégories qu’il y a dans la déficience choisie
	$RqtCat = "SELECT * FROM categorie WHERE id_def_cat = $idDef AND niveau = 1";
	$TabCat = mysqli_query($BDD,$RqtCat);
	
	while($LgnCat = mysqli_fetch_array($TabCat))
	{
?>
					<li>
						<h3><a href="categorie.php?idCat=<?php echo $LgnCat["id_categorie"]; ?>&niv=1"><?php echo $LgnCat["nom_cat"]; ?></a></h3>
<?php
		// Affichage de la description de la première catégorie s’il y en a une
		if($LgnCat["texte_cat"] != NULL OR $LgnCat["texte_cat"] != "")
		{
?>
						<p class="descrp"><?php echo $LgnCat["texte_cat"]; ?></p>
<?php
		}
?>
					</li>
<?php
	}
	
	mysqli_free_result($TabCat);
	mysqli_close($BDD);
?>
				</ul>
			</article>
		</section>
<?php include("bas.php"); ?> 
	</body>
</html>