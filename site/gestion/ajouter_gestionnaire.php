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
		<title>DATÀC – Ajouter une personne</title>
		<meta charset = "utf-8" />
		<link rel = "stylesheet" href = "msf_interne.css"/>
		<link rel = "icon" type = "image/png" href = "../../images/datac_logo.png" />
	</head>
	<body>
		<br/><br/><br/><br/> 
		<header>
			<p class = "haut">Bienvenue sur DATÀC</p>
			<p class = "connexion"><a href = "accueil_gestionnaire.php">Accueil</a> // Ajouter une personne</p>
			<p class = "deconnexion"><a href = "logout.php">Déconnexion </a></p>
		</header>
		<br/><br/><br/>
<?php 
	include("menu.php");
?>
        <section>
			<br/>
            <fieldset> 
                <legend>Ajouter une personne</legend>
                <form method = "POST"> 
					<table> 
						<tr><td>Nom</td><td><input type = "text" id = "nom" name = "nom" size = "40" required /></td></tr>
						<tr><td>Prénom</td><td><input type = "text" id = "prenom" name = "prenom" size = "40" required /></td></tr>
						<tr><td>Login</td><td><input type = "text" id = "login" name = "login" size = "40" required /></td></tr>
						<tr><td>Mot de passe</td><td><input type = "text" id = "mdp" name = "mdp" size = "40" required /></td></tr>
						<tr><td>Adresse mail</td><td><input type = "text" id = "mail" name = "mail" size = "40" required /></td></tr>
						<tr>
							<td>Statut</td>
							<td>
								<select id = "statut" name = "statut">
									<option value = "gestionnaire">Gestionnaire</option>
									<option value = "modérateur">Modérateur</option>
								</select>
							</td>
						</tr>
					</table>
					 <p class = "validation">
						<input type = "submit" name = "btn_envoi" id = "btn_envoi" value = "Ajouter"/>
						<input type = "reset" value = "Annuler" />
					</p>
				</form>
			</fieldset>
			<br/>
		</section>
	</body>
</html>
<?php
	if(isset($_POST["btn_envoi"]))
	{
		$MaRequete = "INSERT INTO `personne`(`id_pers`, `login`, `mdp`, `mail`, `statut`, `Nom` , `Prenom`) VALUES (null,'".$_POST['login']."','".$_POST['mdp']."', '".$_POST['mail']."' , '".$_POST['statut']."' ,'".$_POST['nom']."' , '".$_POST['prenom']."')";
		
		if(mysqli_query($BDD, $MaRequete))
		{
            // Message d'alerte
?>
            <script>
				alert("<?php echo htmlspecialchars('Votre personne a bien été insérée dans la base.', ENT_QUOTES); ?>")
				window.location.href = 'accueil_gestionnaire.php';
			</script>
<?php
		}
	}
}
?>