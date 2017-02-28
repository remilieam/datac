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
		<title>DATÀC – Supprimer une catégorie</title>
		<meta charset = "utf-8" />
		<link rel = "stylesheet" href = "msf_interne.css"/>
		<link rel = "icon" type = "image/png" href = "../../images/datac_logo.png" />
	</head>
	<body>
		<br/><br/><br/><br/> 
		<header>
			<p class = "haut">Bienvenue sur DATÀC</p>
			<p class = "connexion"><a href = "accueil_gestionnaire.php">Accueil</a> // Supprimer une catégorie</p>
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
					<legend>Supprimer une catégorie</legend>
<?php
	// Tableau pour ranger les identifiants des catégories sélectionnées
	$Selections = array();
	
	// Rangement de la déficience
	$Selections[0] = isset($_POST["def"])?$_POST["def"]:null;
        
	if(isset($_POST["def"]))
	{
		// Détermination du niveau maximal que l’on peut atteindre
		$ReqNiv = "SELECT MAX(niveau) FROM categorie WHERE id_def_cat = ".$_POST["def"];
		$TabNiv = mysqli_query($BDD,$ReqNiv);
		$Niveau = mysqli_fetch_array($TabNiv)["MAX(niveau)"];
		
		// Rangement des catégories
		for($i = 1; $i <= $Niveau; $i++)
		{
			$Selections[$i] = isset($_POST["cat$i"])?$_POST["cat$i"]:null;
		}
		
        // Récupération de la dernière catégorie sélectionnée (là ou on veut ajouter la catégorie)
		// Recherche du dernier élément du tableau non nul
		$position = 0;
		$pasTrouve = TRUE;
		
		while($pasTrouve)
		{
			if(!isset($Selections[$position]))
			{
				$pasTrouve = FALSE;
			}
			else
			{
				$position = $position+1;
			}
		}
	}
?>
					<p>Sélectionnez la déficience dans laquelle se trouve la catégorie à supprimer :</p>
					<form method="post" id="choix">
						<!-- Choix de la déficience -->
						<p>
							<select name="def" id="niveau0" onChange="document.forms['choix'].submit();">
								<option value="0">--- Choisissez une déficience ---</option>
<?php
	$Req = "SELECT * FROM deficience";
	$Tab = mysqli_query($BDD,$Req);
	
	// Parcours des déficience
	while($Lec = mysqli_fetch_array($Tab))
	{
		// Si on a sélectionné une défience, on la laisse comme valeur par défaut
		// c’est-à-dire qu’on ajoute dans l’option de la déficience sélectionnée : selected="selected"
?>
								<option value="<?php echo $Lec["id_deficience"]; ?>"<?php echo((isset($Selections[0]) && $Selections[0] == $Lec["id_deficience"])?' selected="selected"':null); ?>><?php echo $Lec["nom_def"]; ?></option>
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
						Choissisez la catégorie que vous souhaitez supprimer, où dans laquelle se trouve la sous-catégorie que vous souhaitez supprimer : <br/>
						<p>
							<select name="cat1" id="niveau1" onChange="document.forms['choix'].submit();">>
								<option value="0">--- Choisissez la catégorie ---</option>
<?php
		$Req = "SELECT * FROM categorie WHERE niveau = 1 AND id_def_cat = ".$Selections[0];
		$Tab = mysqli_query($BDD,$Req);
		
		// Affichage des catégories
		while($Lec = mysqli_fetch_array($Tab))
		{
			// Si on a sélectionné une catégorie, on la laisse comme valeur par défaut
			// c’est-à-dire qu’on ajoute dans l’option de la catégorie sélectionnée : selected="selected"
?>
								<option value="<?php echo $Lec["id_categorie"]; ?>"<?php echo((isset($Selections[1]) && $Selections[1] == $Lec["id_categorie"])?' selected="selected"':null); ?>><?php echo $Lec["nom_cat"]; ?></option>
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
						Choissisez la sous-catégorie que vous souhaitez supprimer (facultatif : <br/>
						<p>
							<select name="cat<?php echo $i; ?>" id="cat<?php echo $i; ?>" onChange="document.forms['choix'].submit();">>
								<option value="0">--- Aucune catégorie choisie ---</option>
<?php
					while($Lec = mysqli_fetch_array($Tab))
					{
						// Si on a sélectionné une catégorie, on la laisse comme valeur par défaut
						// c’est-à-dire qu’on ajoute dans l’option de la catégorie sélectionnée : selected="selected"
?>
								<option value="<?php echo $Lec["id_categorie"]; ?>"<?php echo((isset($Selections[$i]) && $Selections[$i] == $Lec["id_categorie"])?' selected="selected"':null); ?>><?php echo $Lec["nom_cat"]; ?></option>
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
						<input type="submit" name="_supprimer" value="Supprimer"/><br/>
					</form>
				</fieldset>
				<br/>
			</article>
		</section>
	</body>
</html>
<?php
    if(isset($_POST["_supprimer"]))
    {
        // Récupération de la dernière catégorie sélectionnée (celle qu'on veut supprimer)
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
		
		$idCat = ($position != 0)?$Selections[$position-1]:null;
        $idDef = $Selections[0];
        
        if(isset($idCat) && ($position-1) != 0)
        {
            // Suppression de toutes les catégories ayant un niveau plus grand
			
            // Récupération du niveau de la catégorie 
        	$RqtNiv = "SELECT niveau FROM categorie WHERE id_categorie = $idCat";
            $TabNiv = mysqli_query($BDD,$RqtNiv);
            $LecNiv = mysqli_fetch_array($TabNiv);
            $Niveau = $LecNiv["niveau"];
            
            // Récupération du niveau maximum
            $RqtRcNiv = "SELECT MAX(niveau) FROM categorie WHERE id_def_cat = $idDef";
            $TabRcNiv = mysqli_query($BDD,$RqtRcNiv);
            $NiveauMax = mysqli_fetch_array($TabRcNiv)["MAX(niveau)"];
            
            $idCatPrec = $idCat;
            $NivActu = 0;
            
            // Récupération de toutes les catégories étant dans la catégorie à supprimer
            for ($i = $Niveau + 1; $i <= $NiveauMax; $i++) 
            {
            	if(isset($ListeCat))
            	{
		            $j = 0;
	            	$ListeCat[$NivActu] = array();
	            	
	            	for ($k = 0; $k < count($ListeCat[$NivActu-1]); $k++)
	            	{
		            	$RqtRecup = "SELECT id_categorie FROM categorie WHERE id_cat_prec = ".$ListeCat[$NivActu-1][$k];
		            	$TabRecup = mysqli_query($BDD, $RqtRecup);
		            	
		            	if(mysqli_num_rows($TabRecup)!= 0)
		            	{
		            		while($LecRecup = mysqli_fetch_array($TabRecup))
		            		{
		            			$ListeCat[$NivActu][$j] = $LecRecup["id_categorie"];
		            			$j += 1;
		            		}
		            	}
	            	}
	            	
	            	$NivActu += 1;
            	}
            	
            	else
            	{
            		$j = 0;
					$ListeCat[$NivActu] = array();
					
	            	$RqtRecup = "SELECT id_categorie FROM categorie WHERE id_cat_prec = $idCatPrec";
	            	$TabRecup = mysqli_query($BDD, $RqtRecup);
	            	$ListeCat = array();
	            	
	            	if(mysqli_num_rows($TabRecup)!= 0)
	            	{
	            		while($LecRecup = mysqli_fetch_array($TabRecup))
	            		{
	            			$ListeCat[$NivActu][$j] = $LecRecup["id_categorie"];
	            			$j += 1;
	            		}
	            	}
	            	
	            	$NivActu += 1;
            	}
            }
			
			// Suppression de toutes les catégories dans la catégorie à supprimer en commençant par la fin
			for($i = count($ListeCat)-1; $i >= 0; $i--)
			{
				for($j = 0; $j < count($ListeCat[$i]); $j++)
				{
					// Vérification qu'il n'y a de dispositifs dans cette catégorie
					$RqtDispo = "SELECT * FROM dispositif WHERE id_cat_dis = ".$ListeCat[$i][$j];
					$TabDispo = mysqli_query($BDD, $RqtDispo);
					
					if(mysqli_num_rows($TabDispo) != 0)
					{
						while($LecDispo = mysqli_fetch_array($TabDispo))
						{
							// Suppression de tous les prix associés
							$RqtPrix = "DELETE FROM prix WHERE id_dis_prix = ".$LecDispo["id_dispositif"];
							mysqli_query($BDD, $RqtPrix);
							
							// Suppression du dispositif
							$RqtDisp = "DELETE FROM dispositif WHERE id_dispositif = ".$LecDispo["id_dispositif"];
							mysqli_query($BDD, $RqtDisp);
						}
					}
					
					$RqtSuppr = "DELETE FROM categorie WHERE id_categorie = ".$ListeCat[$i][$j];
					mysqli_query($BDD, $RqtSuppr);
				}
			}
			
			$RqtSuppCat = "DELETE FROM categorie WHERE id_categorie = ".$idCat;
			
			if(mysqli_query($BDD,$RqtSuppCat))
			{
				// Message d'alerte
?>
				<script>
					alert("<?php echo htmlspecialchars('La catégorie a bien été supprimée !', ENT_QUOTES); ?>")
					window.location.href = 'accueil_gestionnaire.php';
				</script>
<?php
			}
        }
		
        else
        {
?>
            <script>alert("Veuillez sélectionner une catégorie !");</script>
<?php
        }
    }
}
?>