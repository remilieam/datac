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
		<title>DATÀC – Ajouter un dispositif</title>
		<meta charset = "utf-8" />
		<link rel = "stylesheet" href = "msf_interne.css"/>
		<link rel = "icon" type = "image/png" href = "../../images/datac_logo.png" />
	</head>
	<body>
		<br/><br/><br/><br/> 
		<header>
			<p class = "haut">Bienvenue sur DATÀC</p>
			<p class = "connexion"><a href = "accueil_gestionnaire.php">Accueil</a> // Ajouter un dispositif</p>
			<p class = "deconnexion"><a href = "logout.php">Déconnexion </a></p>
		</header>
		<br/><br/><br/>
<?php 
	include("menu.php");
?>
        <section> 
            <article>
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
				<br/>
				<fieldset> 
					<legend>Ajouter un dispositif</legend>
					<form method = "post" id = "choix" enctype = "multipart/form-data">
						<h3>Placement dans les catégories</h3>
						Sélectionnez une catégorie :
						<!-- Choix de la déficience -->
						<p>
							<select name = "def" id = "niveau0" onChange = "document.forms['choix'].submit();">
								<option value = "0">--- Choisissez une déficience ---</option>
<?php
	$Req = "SELECT * FROM deficience ";
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
				$Tab = mysqli_query($BDD, $Req);
				
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
						<h3>Nom, description et image du dispositif</h3>
						<p>
							Nom du dispositif <sup style = "color : red">*</sup> : 
							<input type = "text" name = "_nom" placeholder = "Marque – Modèle" required />
						</p>
						<p>
							Desription du dispositif <sup style = "color : red">*</sup> :<br/>
							<textarea name = "_description" placeholder = "Écrire une brève description" rows = 10 cols = "60" required></textarea>
						</p>
						<p>
							Image du dispositif :
							<input type = "file" name = "_fichier" />
						</p>
						<h3>Prix et site(s) de vente</h3>
						<p>
							Nom du site de vente <sup style = "color : red">*</sup> :
							<input type = "text" name = "_nomSiteVente1" required />
						</p>
						<p>
							Lien du site de vente <sup style = "color : red">*</sup> : 
							<input type = "text" name = "_urlSiteVente1" placeholder = "www.XXXXX.xx" required />
						</p>
						<p>
							Prix correspondant <sup style = "color : red">*</sup> :
							<input type = "text" name = "_prix1" placeholder = "XX,XX €" required />
						</p>
						<h3>Site du fabricant</h3>
						<p>
							Nom du site du fabricant :
							<input type = "text" name = "_nomSiteFab" />
						</p>
						<p>
							Lien du site du fabricant : 
							<input type = "text" name = "_urlSiteFab" placeholder = "www.XXXXX.xx" />
						</p>
						<input type = "submit" name = "_ajouter" value = "Ajouter"/>
					</form>
					<p><sup style = "color : red">*</sup> : Champ obligatoire</p>
				</fieldset>
				<br/> 
			</article>
		</section>
	</body>
</html>
<?php
    if(isset($_POST["_ajouter"]))
    {
        $nomDispo = strtoupper($_POST["_nom"]);
        $description = $_POST["_description"];
        $idCat = isset($_idCat)?$_idCat:0;
        $idDef = isset($Selections[0])?$Selections[0]:0;
            
        // Vérification de l'existence du produit
        $RqtIdDispo = "SELECT id_dispositif FROM dispositif WHERE description = '$description' AND nom_dis = '$nomDispo'";
        $TabIdDispo = mysqli_query($BDD,$RqtIdDispo);
        $nbDis = mysqli_num_rows($TabIdDispo);
		
        if($nbDis > 1)
        {
?>        
            <script>alert("Ce dispositif existe déjà !"); </script>
<?php
        }
		
		if (($position-1) == 0 || $idDef == 0 || $idCat == 0)
		{
?>        
            <script>alert("Veuillez sélectionner une catégorie"); </script>
<?php
        }
		
        else if (($position-1) != 0 && $idDef != 0 && $idCat != 0)
        {
            // SITE DU FABRICANT
			
            // Vérification qu'il n'existe pas déjà        
            $RqtRechSiteFab = "SELECT * FROM site WHERE url = '".$_POST['_urlSiteFab']."'";
            $TabRechSiteFab = mysqli_query($BDD,$RqtRechSiteFab);
            $LecRechSiteFab = mysqli_fetch_array($TabRechSiteFab);
			
            // S'il n'en existe pas
            if($LecRechSiteFab == NULL)
            {
                // Ajout du site du fabricant
                $nomSiteFab = $_POST["_nomSiteFab"];
                $lienSiteFab = $_POST["_urlSiteFab"];
                $RqtSiteFab = "INSERT INTO site (nom_site,url) VALUES ('$nomSiteFab','$lienSiteFab')";
                $TabSiteFab = mysqli_query($BDD,$RqtSiteFab);
				
                // Récupération de l'identifiant
                $RqtIdF = "SELECT LAST_INSERT_ID() AS id";
                $TabIdF = mysqli_query($BDD,$RqtIdF);
                $LecIdF = mysqli_fetch_array($TabIdF);
                $IdSiteFab = $LecIdF["id"];
            }
			
            else
            {
                // Récupération de l'identifiant
                $IdSiteFab = $LecRechSiteFab['id_site'];
            }
            
            // DISPOSITIF
			
            // Ajout de l'image        
            if(isset($_FILES["_fichier"]) AND $_FILES["_fichier"]["error"] == 0)
            {
				$urlFichier = $_FILES["_fichier"]["name"];
				
				// Vérification que l’image n’existe pas déjà
				$RqtImg = "SELECT * FROM dispositif WHERE image = '".$urlFichier."'";
				$TabImg = mysqli_query($BDD, $RqtImg);
				$LecImg = mysqli_fetch_array($TabImg);
				mysqli_free_result($TabImg);
				
				if($LecImg != null)
				{
					$ReqNom = "UPDATE dispositif SET image = '".$LecImg["id_dispositif"]."_".$urlFichier."' WHERE id_dispositif = ".$LecImg["id_dispositif"];
					mysqli_query($BDD,$ReqNom);
					rename("../images/".$urlFichier,"../images/".$LecImg["id_dispositif"]."_".$urlFichier);
				}
				
				move_uploaded_file($_FILES["_fichier"]["tmp_name"],"../images/".basename($urlFichier));
            }
			
            // Ajout du dispositif
            $RqtDispo = "INSERT INTO dispositif (nom_dis,description,image,id_cat_dis,id_site_fab) VALUES ('$nomDispo','$description','$urlFichier',$idCat,$IdSiteFab)";
            $TabDispo = mysqli_query($BDD,$RqtDispo);
            
            // Récupération de l'id du dispositif
            $RqtIdDi = "SELECT LAST_INSERT_ID() AS id";
            $TabIdDi = mysqli_query($BDD,$RqtIdDi);
            $LecIdDi = mysqli_fetch_array($TabIdDi);
            $idDisp = $LecIdDi["id"];
            
            // SITE DE VENTE
			
            // Vérification qu'il n'existe pas déjà        
            $RqtRechSiteVente = "SELECT * FROM site WHERE url = '".$_POST['_urlSiteVente1']."'";
            $TabRechSiteVente = mysqli_query($BDD,$RqtRechSiteVente);
            $LecRechSiteVente = mysqli_fetch_array($TabRechSiteVente);
			
            // S'il n'en existe pas
            if($LecRechSiteVente == NULL)
            {
                // Ajout du site de vente
                $nomSiteVente = $_POST["_nomSiteVente1"];
                $lienSiteVente = $_POST["_urlSiteVente1"];
                $RqtSiteVente = "INSERT INTO site (nom_site,url) VALUES ('$nomSiteVente','$lienSiteVente')";
                $TabSiteVente = mysqli_query($BDD,$RqtSiteVente);
				
                // Récupération de l'identifiant
                $RqtIdV = "SELECT LAST_INSERT_ID() AS id";
                $TabIdV = mysqli_query($BDD,$RqtIdV);
                $LecIdV = mysqli_fetch_array($TabIdV);
                $IdSiteVente = $LecIdV["id"];
            }
			
            else
            {
                // Récupération de l'identifiant
                $IdSiteVente = $LecRechSiteVente['id_site'];
            }
        
            // PRIX
			
            // Ajout du prix 
            $prix = $_POST['_prix1'];
            $RqtPrix = "INSERT INTO prix (prix,id_dis_prix,id_site_vente) VALUES ('$prix',$idDisp,$IdSiteVente)";
			
			// Si on ajoute le prix, ça veut dire qu'on a bien ajouter le dispositif et le site du fabricant
			if(mysqli_query($BDD,$RqtPrix))
			{
				// Message d'alerte
?>
				<script>
					alert("<?php echo htmlspecialchars('Votre dispositif a bien été ajouté', ENT_QUOTES); ?>")
					window.location.href = 'accueil_gestionnaire.php';
				</script>
<?php
			}
		}
    }
}
?>