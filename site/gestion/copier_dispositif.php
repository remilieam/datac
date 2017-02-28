<!doctype html>
<?php
session_start();
// mise en place d'une sécurité d'accès des pages
// il faut mettre le session_start sinon le empty vérifie que la variable n'exsite pas et
//  sans le session_strart la variable n'exsite pas et donc on est tout le temps dans le if
if (empty($_SESSION['idpers']))
{
	header('Location: page_non_connexion.php');
	exit();
}
else
{
?>
<html>
	<head>
		<title>DATÀC – Dupliquer un dispositif</title>
		<meta charset = "utf-8" />
		<link rel = "stylesheet" href = "msf_interne.css"/>
		<link rel = "icon" type = "image/png" href = "../../images/datac_logo.png" />
	</head>
	<body>
		<br/><br/><br/><br/> 
		<header>
			<p class = "haut">Bienvenue sur DATÀC</p>
			<p class = "connexion"><a href="accueil_gestionnaire.php">Accueil</a> // Dupliquer un dispositif</p>
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
					<legend>Dupliquer un dispositif</legend>
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
		$_idDis = isset($_POST["_idDis"])?$_POST["_idDis"]:null;
		
		// Rangement des catégories
		for($i = 1; $i <= $NiveauBis; $i++)
		{
			$SelectionsBis[$i] = isset($_POST["catBis$i"])?$_POST["catBis$i"] :null;
		}
	}
?>
					<form method = "post" id = "choix">
						<h3>Choississez le dispositif que vous voulez dupliquer</h3>
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
		if(isset($_POST["catBis1"]))
		{
			// Affichage des dispositifs
			$RqtDis = "SELECT id_dispositif, nom_dis FROM dispositif WHERE id_cat_dis=$SelectionsBis[1]";
			$TabDis = mysqli_query($BDD, $RqtDis);
			
			// Vérifie s'il renvoie quelque chose
			if(mysqli_num_rows($TabDis)!=0)
			{
?>
						<!-- Choix du dispositif -->
						<p>
							<select name="_idDis" id="_idDis" onchange="document.forms['choix'].submit();">
								<option value="0">--- Choisissez un dispositif ---</option>
<?php
				// Affichage dans une liste déroulante des déficiences
				while($LecDis = mysqli_fetch_array($TabDis))
				{
?>
								<option value="<?php echo $LecDis["id_dispositif"]; ?>"<?php echo((isset($_idDis) && $_idDis == $LecDis["id_dispositif"])?' selected="selected"':null); ?>><?php echo($LecDis["nom_dis"]); ?></option>
<?php
				}
?>
							</select>
						</p>
<?php
			}
			
			mysqli_free_result($TabDis);
		}
		
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
            
			if(isset($_POST["catBis$i"]))
			{
				// Affichage des dispositifs
				$RqtDis = "SELECT id_dispositif, nom_dis FROM dispositif WHERE id_cat_dis=$SelectionsBis[$i]";
				$TabDis = mysqli_query($BDD, $RqtDis);
				
				// Vérifie s'il renvoie quelque chose
				if(mysqli_num_rows($TabDis)!=0)
				{
?>
						<!-- Choix du dispositif -->
						<p>
							<select name="_idDis" id="_idDis" onchange="document.forms['choix'].submit();">
								<option value="0">--- Choisissez un dispositif ---</option>
<?php
					// Affichage dans une liste déroulante des déficiences
					while($LecDis = mysqli_fetch_array($TabDis))
					{
?>
								<option value="<?php echo $LecDis["id_dispositif"]; ?>"<?php echo((isset($_idDis) && $_idDis == $LecDis["id_dispositif"])?' selected="selected"':null); ?>><?php echo($LecDis["nom_dis"]); ?></option>
<?php
					}
?>
							</select>
						</p>
<?php
                }
                
				mysqli_free_result($TabDis);
			}
		}
                
	}
?>
						<h3> Choississez la catégorie où vous voulez dupliquer le dispositif </h3>
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
                
		// Récupération de la dernière catégorie sélectionnée (là ou on veut ajouter le dispositif)
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
		
		$_idCat = $Selections[$position-1];
	}
?>
						<!-- Choix de la déficience -->
						<p>
							<select name = "def" id = "niveau0" onChange = "document.forms['choix'].submit();">
								<option value = "-1">--- Choisissez une déficience ---</option>
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
	if(isset($_POST["def"]) && $_POST["def"] != -1)
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
						<input type = "submit" name = "_dupliquer" value = "Dupliquer"/>
					</form>
				</article>
			</fieldset>
			<br/>
		</section>
	</body>
</html>
<?php
	// Récupération de l'identifiant de la catégorie
	if(isset($_POST["_dupliquer"]))
	{
        $idCat = isset($_idCat)?$_idCat:0;
        $idDispo = isset($_idDis)?$_idDis:0;
		
        if(isset($idCat) && isset($idDispo) && $idDispo != "" && $idDispo != 0 && $position-1 != 0)
        {
            // Récupération des informations du dispositif
            $RqtRecDis = "SELECT * FROM dispositif WHERE id_dispositif = $idDispo";
            $TabRecDis = mysqli_query($BDD,$RqtRecDis);
            $LecRecDis = mysqli_fetch_array($TabRecDis);
            
            // Assignation des éléments
            $nomDispo = $LecRecDis["nom_dis"];
            $description = $LecRecDis["description"];
            $lienImage = $LecRecDis["image"];
            $idSiteFab = $LecRecDis["id_site_fab"];
            
            // Ajout du dispositif
            $RqtAjout = "INSERT INTO dispositif (nom_dis,description,image,id_cat_dis,id_site_fab) VALUES ('$nomDispo','$description','$lienImage',$idCat,$idSiteFab)";
            $TabAjout = mysqli_query($BDD,$RqtAjout);
            
            // Récupération des informations du prix du dispositif
            $RqtRecDis = "SELECT * FROM prix WHERE id_dis_prix = $idDispo";
            $TabRecDis = mysqli_query($BDD,$RqtRecDis);
            $LecRecDis = mysqli_fetch_array($TabRecDis);
            
            // Récupération de l'id du dispositif
            $RqtIdDi = "SELECT LAST_INSERT_ID() AS id";
            $TabIdDi = mysqli_query($BDD,$RqtIdDi);
            $LecIdDi = mysqli_fetch_array($TabIdDi);
			
            // Assignation des éléments
            $prixDispo = $LecRecDis["prix"];
            $idSiteVente = $LecRecDis["id_site_vente"];
            $idDisp = $LecIdDi["id"];
            
            // Ajout du prix du dispositif
            $RqtPrix = "INSERT INTO prix (prix, id_dis_prix, id_site_vente) VALUES ('$prixDispo','$idDisp','$idSiteVente')";
            
			if(mysqli_query($BDD,$RqtAjout))
			{            
				// Message d'alerte
?>
				<script>
					alert("<?php echo htmlspecialchars('Le dispositif a bien été dupliqué !', ENT_QUOTES); ?>")
					window.location.href='accueil_gestionnaire.php';
				</script>
<?php
			}
        }
		
        else
        {
?>
            <script> alert("Veuillez sélectionner un dispositif et une catégorie !");</script>
<?php
        }
	}
}
?>
