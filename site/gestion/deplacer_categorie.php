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
		<title>DATÀC – Déplacer une catégorie</title>
		<meta charset = "utf-8" />
		<link rel = "stylesheet" href = "msf_interne.css"/>
		<link rel = "icon" type = "image/png" href = "../../images/datac_logo.png" />
	</head>
	<body>
		<br/><br/><br/><br/> 
		<header>
			<p class = "haut">Bienvenue sur DATÀC</p>
			<p class = "connexion"><a href = "accueil_gestionnaire.php">Accueil</a> // Déplacer une catégorie</p>
			<p class = "deconnexion"><a href = "logout.php">Déconnexion </a></p>
		</header>
		<br/><br/><br/>
<?php 
	include("menu.php");
?>
        <section> 
            <article>
            <br/>
            <fieldset> 
                <legend> 
                    Déplacer une catégorie
                </legend>
<?php          
	// Tableau pour ranger les identifiants des catégories sélectionnées
	$SelectionsBis = array();
	
	// Rangement de la déficience
	$SelectionsBis[0] = isset($_POST["defBis"])?$_POST["defBis"] :null;
        
	if(isset($_POST["defBis"]))
	{
		// Détermination du niveau maximal que l’on peut atteindre
		$ReqNivBis = "SELECT MAX(niveau) FROM categorie WHERE id_def_cat = ".$_POST["defBis"];
		$TabNivBis = mysqli_query($BDD,$ReqNivBis);
		$NiveauBis = mysqli_fetch_array($TabNivBis)["MAX(niveau)"];
		
		// Rangement des catégories
		for($i = 1; $i <= $NiveauBis; $i++)
		{
			$SelectionsBis[$i] = isset($_POST["catBis$i"])?$_POST["catBis$i"] :null;
		}
	}
?>
				<form method = "post" id = "choix">
					<h3> Choississez la catégorie que vous voulez déplacer </h3>
					<!-- Choix de la déficience -->
					<p>
						<select name = "defBis" id = "niveau0" onChange = "document.forms['choix'].submit();">
							<option value = "0">--- Choisissez une déficience ---</option>
<?php
	$ReqBis = "SELECT * FROM deficience";
	$TabBis = mysqli_query($BDD,$ReqBis);
	
	// Parcours des déficience
	while($LecBis = mysqli_fetch_array($TabBis))
	{
		// Si on a sélectionné une défience, on la laisse comme valeur par défaut
		// c’est-à-dire qu’on ajoute dans l’option de la déficience sélectionnée : selected = "selected"
?>
							<option value = "<?php echo $LecBis["id_deficience"]; ?>"<?php echo((isset($SelectionsBis[0]) && $SelectionsBis[0] == $LecBis["id_deficience"])?' selected = "selected"' :null); ?>><?php echo $LecBis["nom_def"]; ?></option>
<?php
	}
	
	mysqli_free_result($TabBis);
?>
						</select>
					</p>
<?php
	// Si on a sélectionné une déficience
	if(isset($_POST["defBis"]) && $_POST["defBis"] != 0)
	{
            
?>
					<!-- Choix de la catégorie de niveau 1 -->
					<p>
						<select name = "catBis1" id = "niveau1" onChange = "document.forms['choix'].submit();">>
							<option value = "0">--- Choisissez une catégorie ---</option>
<?php
		$ReqBis = "SELECT * FROM categorie WHERE niveau = 1 AND id_def_cat = ".$SelectionsBis[0];
		$TabBis = mysqli_query($BDD,$ReqBis);
		
		// Affichage des catégories
		while($LecBis = mysqli_fetch_array($TabBis))
		{
			// Si on a sélectionné une catégorie, on la laisse comme valeur par défaut
			// c’est-à-dire qu’on ajoute dans l’option de la catégorie sélectionnée : selected = "selected"
?>
							<option value = "<?php echo $LecBis["id_categorie"]; ?>"<?php echo((isset($SelectionsBis[1]) && $SelectionsBis[1] == $LecBis["id_categorie"])?' selected = "selected"' :null); ?>><?php echo $LecBis["nom_cat"]; ?></option>
<?php
        }
		
		mysqli_free_result($TabBis);
?>
						</select>
					</p>
<?php
		// Cas des sous-catégories (de la deuxième à la dernière)
		for($i = 2; $i <= $NiveauBis; $i++)
		{
			$j = $i - 1;
			
			// Si la catégorie précédente a bien été sélectionnée
			if(isset($_POST["catBis$j"]) && $_POST["catBis$j"] != 0)
			{
				$ReqBis = "SELECT * FROM categorie WHERE id_def_cat = ".$SelectionsBis[0]." AND id_cat_prec = ".$SelectionsBis[$j];
				$TabBis = mysqli_query($BDD,$ReqBis);
				
				// Et s’il existe bien une sous-catégorie
				if(mysqli_num_rows($TabBis) != 0)
				{
?>
					<!-- Choix de la catégorie de niveau <?php echo $i; ?> -->
					<p>
						<select name = "catBis<?php echo $i; ?>" id = "niveau<?php echo $i; ?>" onChange = "document.forms['choix'].submit();">>
							<option value = "0">--- Choisissez une catégorie ---</option>
<?php
					while($LecBis = mysqli_fetch_array($TabBis))
					{
						// Si on a sélectionné une catégorie, on la laisse comme valeur par défaut
						// c’est-à-dire qu’on ajoute dans l’option de la catégorie sélectionnée : selected = "selected"
?>
							<option value = "<?php echo $LecBis["id_categorie"]; ?>"<?php echo((isset($SelectionsBis[$i]) && $SelectionsBis[$i] == $LecBis["id_categorie"])?' selected = "selected"' :null); ?>><?php echo $LecBis["nom_cat"]; ?></option>
<?php
					}
?>
						</select>
					</p>
<?php
				}
			}
		}
	}
?>
                	<h3> Choississez la catégorie où vous voulez déplacer la catégorie choisie précédemment </h3>
<?php
	// Tableau pour ranger les identifiants des catégories sélectionnées
	$Selections = array();
	
	// Rangement de la déficience
	$Selections[0] = isset($_POST["def"])?$_POST["def"] :null;
        
	if(isset($_POST["def"]))
	{
		// Détermination du niveau maximal que l’on peut atteindre
		$ReqNiv = "SELECT MAX(niveau) FROM categorie WHERE id_def_cat = ".$_POST["def"];
		$TabNiv = mysqli_query($BDD,$ReqNiv);
		$Niveau = mysqli_fetch_array($TabNiv)["MAX(niveau)"];
		
		// Rangement des catégories
		for($i = 1; $i <= $Niveau; $i++)
		{
			$Selections[$i] = isset($_POST["cat$i"])?$_POST["cat$i"] :null;
		}
    }
?>
					<!-- Choix de la déficience -->
					<p>
						<select name = "def" id = "niveau0" onChange = "document.forms['choix'].submit();">
							<option value = "0">--- Choisissez une déficience ---</option>
<?php
	$Req = "SELECT * FROM deficience";
	$Tab = mysqli_query($BDD,$Req);
	
	// Parcours des déficience
	while($Lec = mysqli_fetch_array($Tab))
	{
		// Si on a sélectionné une défience, on la laisse comme valeur par défaut
		// c’est-à-dire qu’on ajoute dans l’option de la déficience sélectionnée : selected = "selected"
?>
							<option value = "<?php echo $Lec["id_deficience"]; ?>"<?php echo((isset($Selections[0]) && $Selections[0] == $Lec["id_deficience"])?' selected = "selected"' :null); ?>><?php echo $Lec["nom_def"]; ?></option>
<?php
	}
	
	mysqli_free_result($Tab);
?>
						</select>
					</p>
<?php
	// Si on a sélectionné une déficience
	if(isset($_POST["def"]) && $_POST["def"] != 0)
	{
            
?>
					<!-- Choix de la catégorie de niveau 1 -->
					<p>
						<select name = "cat1" id = "niveau1" onChange = "document.forms['choix'].submit();">>
							<option value = "0">--- Choisissez une catégorie ---</option>
<?php
		$Req = "SELECT * FROM categorie WHERE niveau = 1 AND id_def_cat = ".$Selections[0];
		$Tab = mysqli_query($BDD,$Req);
		
		// Affichage des catégories
		while($Lec = mysqli_fetch_array($Tab))
		{
			// Si on a sélectionné une catégorie, on la laisse comme valeur par défaut
			// c’est-à-dire qu’on ajoute dans l’option de la catégorie sélectionnée : selected = "selected"
?>
							<option value = "<?php echo $Lec["id_categorie"]; ?>"<?php echo((isset($Selections[1]) && $Selections[1] == $Lec["id_categorie"])?' selected = "selected"' :null); ?>><?php echo $Lec["nom_cat"]; ?></option>
<?php
		}
		
		mysqli_free_result($Tab);
?>
						</select>
					</p>
<?php
		// Cas des sous-catégories (de la deuxième à la dernière)
		for($i = 2; $i <= $Niveau; $i++)
		{
			$j = $i - 1;
			
			// Si la catégorie précédente a bien été sélectionnée
			if(isset($_POST["cat$j"]) && $_POST["cat$j"] != 0)
			{
				$Req = "SELECT * FROM categorie WHERE id_def_cat = ".$Selections[0]." AND id_cat_prec = ".$Selections[$j];
				$Tab = mysqli_query($BDD,$Req);
				
				// Et s’il existe bien une sous-catégorie
				if(mysqli_num_rows($Tab) != 0)
				{
?>
			
					<!-- Choix de la catégorie de niveau <?php echo $i; ?> -->
					<p>
						<select name = "cat<?php echo $i; ?>" id = "cat<?php echo $i; ?>" onChange = "document.forms['choix'].submit();">>
							<option value = "0">--- Choisissez une catégorie ---</option>
<?php
					while($Lec = mysqli_fetch_array($Tab))
					{
						// Si on a sélectionné une catégorie, on la laisse comme valeur par défaut
						// c’est-à-dire qu’on ajoute dans l’option de la catégorie sélectionnée : selected = "selected"
?>
							<option value = "<?php echo $Lec["id_categorie"]; ?>"<?php echo((isset($Selections[$i]) && $Selections[$i] == $Lec["id_categorie"])?' selected = "selected"' :null); ?>><?php echo $Lec["nom_cat"]; ?></option>
<?php
					}
?>
						</select> 
					</p>
<?php
				}
				
				mysqli_free_result($Tab);
			}
		}
                
	}
?>
                    <input type = "submit" name = "_deplacer" value = "Déplacer"/>
                </form>
                </fieldset>
				<br/> 
            </article>
        </section>
    </body>
</html>

<?php
	// Récupération de l'identifiant de la catégorie
	if(isset($_POST["_deplacer"]))
	{
        // Récupération de la dernière catégorie sélectionnée (là ou on veut ajouter la catégorie)
        // Recherche du dernier élément du tableau non nul
        $positionBis = 0;
		$pasTrouveBis = TRUE;
		
		while($pasTrouveBis)
		{
			if(!isset($SelectionsBis[$positionBis]) || $SelectionsBis[$positionBis] == 0)
			{
				$pasTrouveBis = FALSE;
			}
			else
			{
				$positionBis = $positionBis+1;
			}
		}
        
        // Récupération de la dernière catégorie sélectionnée (celle qu'on veut déplacer)
        // Recherche du dernier élément du tableau non nul
        $position = 0;
		$pasTrouve = TRUE;
		
		while($pasTrouve)
		{
			if(!isset($Selections[$position]) || $Selections[$position] == 0)
			{
				$pasTrouve = FALSE;
			}
			else
			{
				$position = $position+1;
			}
		}
		
		$idCat1 = ($positionBis != 0)?$SelectionsBis[$positionBis-1]:null;
		$idCat2 = ($position != 0)?$Selections[$position-1]:null;
        $idDef1 = $SelectionsBis[0];
        $idDef2 = $Selections[0];
        
        if(isset($idCat1) && isset($idCat2) && ($position-1) != 0 && ($positionBis-1) != 0)
        {
        	// Récupération du niveau de la catégorie 
        	$RqtNiv = "SELECT niveau FROM categorie WHERE id_categorie = $idCat2";
            $TabNiv = mysqli_query($BDD,$RqtNiv);
            $LecNiv = mysqli_fetch_array($TabNiv);
            $niveau2 = $LecNiv["niveau"]+1;
        	
            // Modification de la déficience pour toutes les catégories suivantes
            // Récupération du niveau de la catégorie 
        	$RqtNiv1 = "SELECT niveau FROM categorie WHERE id_categorie = $idCat1";
            $TabNiv1 = mysqli_query($BDD,$RqtNiv1);
            $LecNiv1 = mysqli_fetch_array($TabNiv1);
            $niveau1 = $LecNiv1["niveau"];
            
            // Récupération du niveau maximum
            $RqtRcNiv = "SELECT MAX(niveau) FROM categorie WHERE id_def_cat = $idDef1";
            $TabRcNiv = mysqli_query($BDD,$RqtRcNiv);
            $NiveauMax = mysqli_fetch_array($TabRcNiv)["MAX(niveau)"];
            
            // Modification de la position de la catégorie
            $RqtCat = "UPDATE categorie SET id_def_cat = $idDef2, id_cat_prec = $idCat2, niveau = $niveau2 WHERE id_categorie = $idCat1";
            $TabCat = mysqli_query($BDD,$RqtCat);
            
            // Boucle pour actualiser l'id de la déficience des catégories
            $idCatPrec = $idCat1;
            $niv = 1;
            
            for ($i = $niveau1 + 1; $i <= $NiveauMax; $i++) 
            {
            	if(isset($listeCat))
            	{
		            $j = 0;
		            $listeCatPrec = array();
		            $listeCatPrec = $listeCat;
	            	$listeCat = array();
	            	
	            	for ($k = 0; $k < count($listeCatPrec); $k++)
	            	{
	            		 
		            	$RqtRecup = "SELECT id_categorie FROM categorie WHERE id_cat_prec = ".$listeCatPrec[$k];
		            	$TabRecup = mysqli_query($BDD, $RqtRecup);
		            	
		            	if(mysqli_num_rows($TabRecup)!= 0)
		            	{
		            		while($LecRecup = mysqli_fetch_array($TabRecup))
		            		{
		            			$listeCat[$j] = $LecRecup["id_categorie"];
								$RqtMAJ = "UPDATE categorie SET id_def_cat = $idDef2, niveau = ".($niveau2+$niv)." WHERE id_categorie = ".$listeCat[$j];
								$TabMAJ = mysqli_query($BDD,$RqtMAJ);
		            			$j += 1;
		            		}
		            	}
	            	}
	            	
	            	$niv += 1;
            	}
            	
            	else
            	{
            		$j = 0;
	            	$RqtRecup = "SELECT id_categorie FROM categorie WHERE id_cat_prec = $idCatPrec";
	            	$TabRecup = mysqli_query($BDD, $RqtRecup);
	            	$listeCat = array();
	            	
	            	if(mysqli_num_rows($TabRecup)!= 0)
	            	{
	            		while($LecRecup = mysqli_fetch_array($TabRecup))
	            		{
	            			$listeCat[$j] = $LecRecup["id_categorie"];
							$RqtMAJ = "UPDATE categorie SET id_def_cat = $idDef2, niveau = ".($niveau2+$niv)." WHERE id_categorie = ".$listeCat[$j];
							$TabMAJ = mysqli_query($BDD,$RqtMAJ);
	            			$j += 1;
	            		}
	            	}
	            	
	            	$niv += 1;
            	}
            }

			// Message d'alerte
?>
            <script>
				alert("<?php echo htmlspecialchars('La catégorie a bien été déplacée !', ENT_QUOTES); ?>")
				window.location.href = 'accueil_gestionnaire.php';
			</script>
<?php
        }
		
		else if (($position-1) == 0 && ($positionBis-1) != 0)
		{
			
        	// Le niveau est le 1, la catégorie sélectionnée étant une déficience (de niveau 0)
            $niveau2 = 1;
        	
            // Modification de la déficience pour toutes les catégories suivantes
            // Récupération du niveau de la catégorie 
        	$RqtNiv1 = "SELECT niveau FROM categorie WHERE id_categorie = $idCat1";
            $TabNiv1 = mysqli_query($BDD,$RqtNiv1);
            $LecNiv1 = mysqli_fetch_array($TabNiv1);
            $niveau1 = $LecNiv1["niveau"];
            
            // Récupération du niveau maximum
            $RqtRcNiv = "SELECT MAX(niveau) FROM categorie WHERE id_def_cat = $idDef1";
            $TabRcNiv = mysqli_query($BDD,$RqtRcNiv);
            $NiveauMax = mysqli_fetch_array($TabRcNiv)["MAX(niveau)"];
            
            // Modification de la position de la catégorie
            $RqtCat = "UPDATE categorie SET id_def_cat = $idDef2, id_cat_prec = NULL, niveau = $niveau2 WHERE id_categorie = $idCat1";
            $TabCat = mysqli_query($BDD,$RqtCat);
            
            // Boucle pour actualiser l'id de la déficience des catégories
            $idCatPrec = $idCat1;
            $niv = 1;
            
            for ($i = $niveau1 + 1; $i <= $NiveauMax; $i++) 
            {
            	if(isset($listeCat))
            	{
		            $j = 0;
		            $listeCatPrec = array();
		            $listeCatPrec = $listeCat;
	            	$listeCat = array();
	            	
	            	for ($k = 0; $k < count($listeCatPrec); $k++)
	            	{
	            		 
		            	$RqtRecup = "SELECT id_categorie FROM categorie WHERE id_cat_prec = ".$listeCatPrec[$k];
		            	$TabRecup = mysqli_query($BDD, $RqtRecup);
		            	
		            	if(mysqli_num_rows($TabRecup)!= 0)
		            	{
		            		while($LecRecup = mysqli_fetch_array($TabRecup))
		            		{
		            			$listeCat[$j] = $LecRecup["id_categorie"];
								$RqtMAJ = "UPDATE categorie SET id_def_cat = $idDef2, niveau = ".($niveau2+$niv)." WHERE id_categorie = ".$listeCat[$j];
								$TabMAJ = mysqli_query($BDD,$RqtMAJ);
		            			$j += 1;
		            		}
		            	}
	            	}
	            	
	            	$niv += 1;
            	}
            	
            	else
            	{
            		$j = 0;
	            	$RqtRecup = "SELECT id_categorie FROM categorie WHERE id_cat_prec = $idCatPrec";
	            	$TabRecup = mysqli_query($BDD, $RqtRecup);
	            	$listeCat = array();
	            	
	            	if(mysqli_num_rows($TabRecup)!= 0)
	            	{
	            		while($LecRecup = mysqli_fetch_array($TabRecup))
	            		{
	            			$listeCat[$j] = $LecRecup["id_categorie"];
							$RqtMAJ = "UPDATE categorie SET id_def_cat = $idDef2, niveau = ".($niveau2+$niv)." WHERE id_categorie = ".$listeCat[$j];
							$TabMAJ = mysqli_query($BDD,$RqtMAJ);
	            			$j += 1;
	            		}
	            	}
	            	
	            	$niv += 1;
            	}
            }

			// Message d'alerte
?>
            <script>
				alert("<?php echo htmlspecialchars('La catégorie a bien été déplacée !', ENT_QUOTES); ?>")
				window.location.href = 'accueil_gestionnaire.php';
			</script>
<?php
		}
        
        else
        {
?>
            <script>alert("Veuillez sélectionner deux catégories !");</script>
<?php
        }
	}
}
?>