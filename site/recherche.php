<!DOCTYPE html>
<?php
	// Connexion à la base de données
	require("connect.php");
	mysqli_set_charset($BDD, "utf8");

	// Récupération du niveau et du nom de la catégorie sur laquelle on vient de cliquer
	$recherche = $_POST["recherche"];
	$tabRecherche = explode(' ',trim($recherche));
	$tabResultats = array();
	
	// on parcourt tous les dispositifs et on regarde pour lesquels il y a des correspondances dans : le dispositif (nom et texte), les catégories (noms et textes), et la déficience (nom et texte)
	$RqtDispositifs = "SELECT * FROM dispositif";
	$TabDispositifs = mysqli_query($BDD, $RqtDispositifs);
	while ($LgnDispositifs = mysqli_fetch_array($TabDispositifs))
	{
		$okAll = true;
		foreach ($tabRecherche as $mot)
		{
			if ($mot != "")
			{
				$ok = false;
				$mot = strtolower(trim($mot));
				// on regarde dans les métadonnées du dispositif
				if (strpos(strtolower($LgnDispositifs["nom_dis"]),$mot) || strpos(strtolower($LgnDispositifs["description"]),$mot)) $ok = true;
				if (!$ok)
				{
					// on regarde dans les métadonnées de la catégorie
					$RqtCatDis = "SELECT * FROM categorie WHERE id_categorie = ".$LgnDispositifs["id_cat_dis"];
					$TabCatDis = mysqli_query($BDD, $RqtCatDis);
					$LgnCatDis = mysqli_fetch_array($TabCatDis);
					if (strpos(strtolower($LgnCatDis['nom_cat']),$mot) || strpos(strtolower($LgnCatDis['texte_cat']),$mot)) $ok = true;
					if (!$ok)
					{
						// on regarde dans les métadonnées des catégories précédentes
						$cat = $LgnCatDis['id_cat_prec'];
						while ($cat != null)
						{
							$RqtCatPrec = "SELECT * FROM categorie WHERE id_categorie = ".$cat;
							$TabCatPrec = mysqli_query($BDD, $RqtCatPrec);
							$LgnCatPrec = mysqli_fetch_array($TabCatPrec);
							if (strpos(strtolower($LgnCatPrec['nom_cat']),$mot) || strpos(strtolower($LgnCatPrec['texte_cat']),$mot)) $ok = true;
							$cat = $LgnCatPrec['id_cat_prec'];
							
							mysqli_free_result($TabCatPrec);
						}
						if (!$ok && $LgnCatPrec['id_def_cat'] != null)
						{
							// on regarde dans les métadonnées de la déficience
							$RqtDefCatDis = "SELECT * FROM deficience WHERE id_deficience = ".$LgnCatPrec['id_def_cat'];
							$TabDefCatDis = mysqli_query($BDD, $RqtDefCatDis);
							$LgnDefCatDis = mysqli_fetch_array($TabDefCatDis);
							if (strpos(strtolower($LgnDefCatDis['nom_def']),$mot) || strpos(strtolower($LgnDefCatDis['texte_def']),$mot)) $ok = true;
							
							mysqli_free_result($TabDefCatDis);
						}
					}
					
					mysqli_free_result($TabCatDis);
				}
				if (!$ok) $okAll = false;	
			}
		}
		if ($okAll) $tabResultats[] = $LgnDispositifs['id_dispositif'];
	}
	mysqli_free_result($TabDispositifs);
	
?>

<html>
	<head>
		<meta charset="UTF-8">
		<title>DATÀC – Recherche</title>
		<link rel =" stylesheet" href ="mise_en_forme.css"/>
		<link rel="icon" type="image/png" href="../images/datac_logo.png" />
	</head>
	<body>
		<header>
			<h1>Dispositifs et aides techniques à la communication</h1>
			<!--Affichage du fil d’Ariane-->
			<p class="ariane">
				<a href="accueil.php">Accueil</a> // <?php echo "Recherche : « ".$recherche." »"; ?> 
			</p>
<?php include("bouton_recherche.php"); ?>
<?php include("bouton_connexion.php"); ?>
		</header>
		<section>

			<article>
				<p>Il existe les produits suivants :</p>
<?php
		if (count($tabResultats) != 0) 
		{
			$RqtResultatsDis = "SELECT * FROM dispositif WHERE id_dispositif IN (".implode(',',$tabResultats).") ORDER BY nom_dis";
			$TabResultatsDis = mysqli_query($BDD, $RqtResultatsDis);
		
			// affichage des résultats
			while ($LgnResultatsDis = mysqli_fetch_array($TabResultatsDis))
			{
	?>
					<p class="liste_produit"> 
						<a href="dispositif.php?idDis=<?php echo $LgnResultatsDis["id_dispositif"]; ?>"><?php echo $LgnResultatsDis["nom_dis"]; ?></a>
					</p>
	<?php
			}
			mysqli_free_result($TabResultatsDis);
		}
		else
		{
			echo "Pas de résultats.";
		}
?>
			</article>
<?php
	
	mysqli_close($BDD);
?>
			</article>
		</section>
	</body>
</html>