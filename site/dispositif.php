<!DOCTYPE html>
<?php
    // Connexion à la base de données
    require("connect.php");
	mysqli_set_charset($BDD, "utf8");

    // Récupération de l'identifiant du dispositif sur lequel on vient de cliquer
    $idDis = $_GET["idDis"];

    // Récupération des informations liées au dispositif
    $RqtDis = "SELECT * FROM dispositif WHERE id_dispositif = $idDis";
    $TabDis = mysqli_query($BDD,$RqtDis);
    $LgnDis = mysqli_fetch_array($TabDis);
	mysqli_free_result($TabDis);
	
    // Assignation à des variables
    $nomDis = $LgnDis["nom_dis"];
    $description = $LgnDis["description"];
    $idCat = $LgnDis["id_cat_dis"];
    $idSiteFab = $LgnDis["id_site_fab"];

    //Récupération du/des prix du dispositif 
    $RqtPrix = "SELECT * FROM prix WHERE id_dis_prix = $idDis";
    $TabPrix = mysqli_query($BDD,$RqtPrix);
	
    // Assignation du/des prix et des sites correspondants dans un tableau  
    $tableauPrixSiteV = array();
    $i = 0;
	
    while($LgnPrix = mysqli_fetch_array($TabPrix))
    {
        $idPrix = $LgnPrix["id_prix"];
		$prix = $LgnPrix["prix"];
        
		if($LgnPrix["id_site_vente"] != NULL)
		{
			// Récupération du site vendeur correspondant au prix
			$RqtVend = "SELECT * FROM site WHERE id_site = ".$LgnPrix["id_site_vente"];
			$TabVend = mysqli_query($BDD, $RqtVend);
			$LgnVend = mysqli_fetch_array($TabVend);
			mysqli_free_result($TabVend);
			
			$urlSiteVend = $LgnVend["url"];
			$nomSiteVend = $LgnVend["nom_site"];
		}
		
		else 
		{
			$urlSiteVend = "";
			$nomSiteVend = "";
		}
                
        // Assignation des données dans un tableau
        $tableauPrixSiteV[$i] = array($prix,$urlSiteVend,$nomSiteVend);
        
        $i++;
    }
	
    // Nombre de prix pour un dispositif
    $nbPrix = $i;
    
    // Récupération du site fabricant
	if($idSiteFab != NULL)
	{
		$RqtFab = "SELECT url, nom_site FROM site WHERE id_site = $idSiteFab";
		$TabFab = mysqli_query($BDD, $RqtFab);
		$LgnFab = mysqli_fetch_array($TabFab);
		mysqli_free_result($TabFab);
		
		$urlSiteFab = $LgnFab["url"];
		$nomSiteFab = $LgnFab["nom_site"];
	}
	
	else 
	{
		$urlSiteFab = "";
		$nomSiteFab = "";
	}
	       
    // Récupération d’informations pour le fil d’Ariane
    $RqtFil = "SELECT nom_def, nom_cat, niveau, id_deficience, id_cat_prec FROM categorie, deficience WHERE id_def_cat = id_deficience AND id_categorie = $idCat";
    $TabFil = mysqli_query($BDD, $RqtFil);
    $LgnFil = mysqli_fetch_array($TabFil);
	mysqli_free_result($TabFil);
	
    // Assignation à des variables
    $nomDef = $LgnFil["nom_def"];
    $nomCat = $LgnFil["nom_cat"];
    $niv = $LgnFil["niveau"];
    $idDef = $LgnFil["id_deficience"];
    $idCatPrec = $LgnFil["id_cat_prec"];
    
     // Utilisation d’un tableau pour recenser les noms des catégories précédentes
    $tabCat[0] = $nomDef;
	$tabCat[$niv] = $nomCat;
	
    // Utilisation d’un tableau pour recenser les identifiants des catégories précédentes
    $tabCatId[0] = $idDef;
    $tabCatId[$niv] = $idCat;
    
    // Assignation dans les tableaux des noms et identifiants de chaque catégorie
    for($i = 1; $i < $niv; $i++)
    {
        $RqtCatPrec = "SELECT * FROM categorie WHERE id_categorie = $idCatPrec";
        $TabCatPrec = mysqli_query($BDD,$RqtCatPrec);

        if(mysqli_num_rows($TabCatPrec) != 0)
        {
            $LgnCatPrec = mysqli_fetch_array($TabCatPrec);
            
			$tabCat[$niv-$i] = $LgnCatPrec["nom_cat"];
            $tabCatId[$niv-$i] = $idCatPrec;
            
            $idCatPrec = $LgnCatPrec["id_cat_prec"];
        }

        mysqli_free_result($TabCatPrec);
    }
?>

<html>
	<head>
		<meta charset="UTF-8">
		<title>DATÀC – <?php print $nomDis; ?></title>
		<link rel =" stylesheet" href ="mise_en_forme.css"/>
		<link rel="icon" type="image/png" href="../images/datac_logo.png" />
	</head>
	<body>
		<header>
			<h1>Dispositifs et aides techniques à la communication</h1>
			<!--Affichage du fil d’Ariane-->
			<p class="ariane">
				<a href="accueil.php">Accueil</a> 
				// <a href="deficience.php?idDef=<?php echo $tabCatId[0]; ?>"> <?php echo $tabCat[0]; ?></a>
<?php
	// Pour les sous-catégories
	for($i = 1; $i <= $niv ; $i++)
	{
?>
                > <a href="categorie.php?idCat=<?php echo $tabCatId[$i]; ?>&niv=<?php echo $i; ?>"> <?php echo $tabCat[$i]; ?></a> 
<?php
	}
	
	// Affichage du dispositif
?>
				> <?php echo $nomDis; ?>  
			</p>
<?php include("bouton_recherche.php"); ?> 
<?php include("bouton_connexion.php"); ?> 
		</header>
		<section>
			<article>
<?php
	// Affichage du nom du dispositif et de sa description
?>
				<p class="titre_dispositif"><?php echo $nomDis; ?></p>
				<div style="float:left;width:25%;margin-right:10px">
					<a href="images/<?php echo $LgnDis["image"]; ?>" target="_blank">
						<img style="width:100%" src="images/<?php echo $LgnDis["image"]; ?>" alt="<?php echo $LgnDis["nom_dis"]; ?>" title="<?php echo $LgnDis["nom_dis"]; ?>"/>
					</a>
				</div>
				<p style="text-align:justify"><?php echo $description; ?></p>
<?php
	// Affichage du/des sites de vente et du/des prix correspondants
?>
				<table style="clear:both">
					<tr class="dispositif">
						<td class="dispositif">Prix et site(s) de vente :</td>
						<td><?php
	if($nbPrix != 0)
	{
		for($i = 0; $i < $nbPrix; $i++)
		{
			echo $tableauPrixSiteV[$i][0];
			
			if($tableauPrixSiteV[$i][1] != "")
			{
				?> sur <a href="http://<?php echo $tableauPrixSiteV[$i][1];?>" target="_blank"><?php echo $tableauPrixSiteV[$i][2]; ?></a><br/><?php
			}
			
			else { echo " sur ".$tableauPrixSiteV[$i][2]; }
		}
	}
	
	else 
	{
		echo "Inconnus";
	}
						?><td>
					</tr>
<?php
	// Affichage du site du fabricant
	if($urlSiteFab != "")
	{
?>
					<tr>
						<td>Site du fabricant :</td>
						<td><a href="http://<?php echo $urlSiteFab;?>"> <?php echo $nomSiteFab; ?></a></td>
					</tr>
<?php
	}
	
	else 
	{
?>
					<tr>
						<td>Site du fabricant :</td>
						<td>Inconnu</td>
					</tr>
<?php
	}
	
	$ReqSim = "SELECT * FROM dispositif WHERE id_cat_dis = ".$idCat." AND id_dispositif <> ".$idDis;
	$TabSim = mysqli_query($BDD,$ReqSim);
	
	if(mysqli_num_rows($TabSim) != 0)
	{
?>
					<tr>
						<td>Voir aussi :</td>
						<td>
<?php
		while($LgnSim = mysqli_fetch_array($TabSim))
		{
?>
							<a href="dispositif.php?idDis=<?php echo $LgnSim["id_dispositif"]; ?>"><?php echo $LgnSim["nom_dis"]; ?></a><br/>
<?php
		}
?>
						</td>
					</tr>
				</table>
<?php
	}
	
	else 
	{
?>
				</table>
<?php
	}
	
	mysqli_close($BDD);
?>
			</article>
		</section>
<?php include("bas.php"); ?> 
	</body>
</html>