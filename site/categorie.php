<!DOCTYPE html>
<?php
	// Connexion à la base de données
	require("connect.php");
	mysqli_set_charset($BDD, "utf8");

	// Récupération du niveau et du nom de la catégorie sur laquelle on vient de cliquer
	$niv = $_GET["niv"];
	$idCat = $_GET["idCat"];
	
	// Recherche de l’indentifiant et du nom de la catégorie sur laquelle on vient de cliquer et la déficience à laquelle elle appartient
	$RqtRecup = "SELECT * FROM deficience, categorie WHERE id_deficience = id_def_cat AND id_categorie = $idCat";
	$TabRecup = mysqli_query($BDD, $RqtRecup);
	$LgnRecup = mysqli_fetch_array($TabRecup);
	$nomDef = mysqli_fetch_array($TabRecup);
	mysqli_free_result($TabRecup);

	// Assignation à des variables
	$idDef = $LgnRecup["id_deficience"];
	$idCatPrec = $LgnRecup["id_cat_prec"];
	$nomDef = $LgnRecup["nom_def"];
	$nomCat = $LgnRecup["nom_cat"];

	// Utilisation d’un tableau pour recenser les noms des catégories précédentes
	$tabCat[0] = $nomDef;
	$tabCat[$niv] = $nomCat;

	// Utilisation d’un tableau pour recenser les identifiants des catégories précédentes
	$tabCatId[0] = $idDef;
	$tabCatId[$niv] = $idCat;

	// Assignation dans les tableaux des noms et identifiants de chaque catégorie
	for ($i = 1; $i < $niv; $i++)
	{
		$RqtCatPrec = "SELECT * FROM categorie WHERE id_categorie = $idCatPrec";
		$TabCatPrec = mysqli_query($BDD, $RqtCatPrec);

		if (mysqli_num_rows($TabCatPrec) != 0)
		{
			$LgnCatPrec = mysqli_fetch_array($TabCatPrec);

			$tabCat[$niv - $i] = $LgnCatPrec["nom_cat"];
			$tabCatId[$niv - $i] = $idCatPrec;

			$idCatPrec = $LgnCatPrec["id_cat_prec"];
		}

		mysqli_free_result($TabCatPrec);
	}
?>

<html>
	<head>
		<meta charset="UTF-8">
		<title>DATÀC – <?php echo $nomCat; ?></title>
		<link rel =" stylesheet" href ="mise_en_forme.css"/>
		<link rel="icon" type="image/png" href="../images/datac_logo.png" />
	</head>
	<body>
		<header>
			<h1>Dispositifs et aides techniques à la communication</h1>
			<!--Affichage du fil d’Ariane-->
			<p class="ariane">
				<a href="accueil.php">Accueil</a>
				// <a href="deficience.php?idDef=<?php echo $tabCatId[0]; ?>"><?php echo $tabCat[0]; ?></a>
<?php
			// Pour les sous-catégories
			for ($i = 1; $i < $niv; $i++)
			{
?>
				> <a href="categorie.php?idCat=<?php echo $tabCatId[$i]; ?>&niv=<?php echo $i; ?>"><?php echo $tabCat[$i]; ?></a>
<?php
			}

			// Affichage de la dernière catégorie
?>
				> <?php echo $tabCat[$niv]; ?> 
			</p>
<?php include("bouton_recherche.php"); ?> 
<?php include("bouton_connexion.php"); ?> 
		</header>
		<section>
<?php
	// On regarde s’il existe des sous-catégories à la catégorie sur laquelle on vient de cliquer
	$RqtFin = "SELECT * FROM categorie WHERE id_cat_prec = $idCat";
	$TabFin = mysqli_query($BDD, $RqtFin);
	
	// Et s’il existe des dispositifs dans la catégorie sur laquelle on vient de cliquer
	$RqtFnl = "SELECT * FROM dispositif WHERE id_cat_dis = $idCat";
	$TabFnl = mysqli_query($BDD, $RqtFnl);

	// Si oui, on génère la page d’affichage de la liste des dispositifs
	if (mysqli_num_rows($TabFnl) != 0 || mysqli_num_rows($TabFin) == 0) 
	{
?>
			<article>
				<p>Il existe les produits suivants :</p>
<?php
		// Recherche et affichage de tous les dispositifs relatifs à la catégorie choisie
		$RqtDis = "SELECT * FROM dispositif WHERE id_cat_dis = $idCat";
		$TabDis = mysqli_query($BDD, $RqtDis);
		
		while ($LgnDis = mysqli_fetch_array($TabDis))
		{
?>
				<p class="liste_produit"> 
					<a href="dispositif.php?idDis=<?php echo $LgnDis["id_dispositif"]; ?>"><?php echo $LgnDis["nom_dis"]; ?></a>
				</p>
<?php
		}
?>
			</article>
<?php
		mysqli_free_result($TabDis);
	}
	
	// S’il n’y a pas de dispositifs, on génère la page d’affichage de la liste des sous-catégories
	if (mysqli_num_rows($TabFin) != 0) 
	{
?>
			<article>
				<p>Il existe les catégories suivantes :</p>
				<ul>
<?php
		// Recherche et affichage des toutes les sous-catégories relatives à la catégorie choisie
		$RqtCatSuiv = "SELECT * FROM categorie WHERE id_cat_prec = $idCat AND niveau = ($niv+1)";
		$TabCatSuiv = mysqli_query($BDD, $RqtCatSuiv);

		while ($LgnCatSuiv = mysqli_fetch_array($TabCatSuiv))
		{
?>
					<li>
						<h3><a href="categorie.php?idCat=<?php echo $LgnCatSuiv["id_categorie"];?>&niv=<?php echo $niv + 1; ?>"><?php echo $LgnCatSuiv["nom_cat"]; ?></a></h3>
<?php
			// Affichage de la description de la première catégorie s’il y en a une
			if ($LgnCatSuiv["texte_cat"] != NULL OR $LgnCatSuiv["texte_cat"] != "")
			{
?>
						<p class="descrp"><?php echo $LgnCatSuiv["texte_cat"]; ?></p>
<?php
			}
?>
					</li>
<?php
		}
?>
				</ul>
			</article>
<?php
		mysqli_free_result($TabCatSuiv);
	}

	mysqli_free_result($TabFin);
	mysqli_close($BDD);
?>
		</section>
<?php include("bas.php"); ?> 
	</body>
</html>