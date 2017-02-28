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
		<title>DATÀC – Ajouter une déficience</title>
		<meta charset = "utf-8" />
		<link rel = "stylesheet" href = "msf_interne.css"/>
		<link rel = "icon" type = "image/png" href = "../../images/datac_logo.png" />
	</head>
	<body>
		<br/><br/><br/><br/> 
		<header>
			<p class = "haut">Bienvenue sur DATÀC</p>
			<p class = "connexion"><a href = "accueil_gestionnaire.php">Accueil</a> // Ajouter une déficience</p>
			<p class = "deconnexion"><a href = "logout.php">Déconnexion </a></p>
		</header>
		<br/><br/><br/>
<?php 
	include("menu.php");
?>
        <section>
			<br/>
            <fieldset> 
                <legend>Ajouter une déficience</legend>
				<form method = "POST">
					<table>
						<tr>
							<td>Écrivez la déficience que vous souhaitez ajouter :</td>
							<td><input type = "text" name = "nom_def" required/></td>
						</tr>
						<tr>
							<td>Écrivez une description de la déficience :</td>
							<td><textarea name = "texte_def" rows = "8" cols = "60" required/></textarea></td>
						</tr>
						<tr><td></td><td><input type = "submit" value = "Ajouter"/></td></tr>
					</table>
				</form>
			</fieldset>
			<br/>
		</section>
    </body>
</html>
<?php
	if(isset($_POST['nom_def']) && isset($_POST['texte_def']))
	{
		$nom_def = $_POST['nom_def'];
		$texte_def = $_POST['texte_def'];
		
		if($BDD)
		{             
			$ReqAjDef = "INSERT INTO deficience VALUES ('','$nom_def', '$texte_def')";
			
			if(mysqli_query($BDD, $ReqAjDef))
			{
?>
				<script>
					alert("<?php echo htmlspecialchars('Votre déficience a bien été ajoutée', ENT_QUOTES); ?>")
					window.location.href = 'accueil_gestionnaire.php';
				</script>
<?php
			}
		}
	}
}
?>