<!doctype html>
<?php
session_start();
// mise en place d'une sécurité d'accès des pages
// il faut mettre le session_start sinon le empty vérifie que la variable n'exsite pas et
// sans le session_strart la variable n'exsite pas et donc on est tout le temps dans le if
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
		<title>DATÀC – Modifier son compte</title>
		<meta charset = "utf-8" />
		<link rel = "stylesheet" href = "msf_interne.css"/>
		<link rel = "icon" type = "image/png" href = "../../images/datac_logo.png" />
	</head>
	<body>
		<br/><br/><br/><br/> 
		<header>
			<p class = "haut">Bienvenue sur DATÀC</p>
			<p class = "connexion"><a href = "accueil_gestionnaire.php">Accueil</a> // Modifier son compte</p>
			<p class = "deconnexion"><a href = "logout.php">Déconnexion </a></p>
		</header>
		<br/><br/><br/>
<?php 
	include("menu.php");
	
	$RqtInfo = "SELECT * FROM personne WHERE id_pers = ".$_SESSION['idpers'];
	$TabInfo = mysqli_query($BDD, $RqtInfo);
	$LecInfo = mysqli_fetch_array($TabInfo);
?>
		<section>
			<br/>
			<fieldset>
				<legend>Modifier les données du compte</legend>
				<p class = "consigne">
					Vous souhaitez modifier les renseignements sur votre compte.
					Vous pouvez remplir autant de lignes dans le formulaire ci-dessous que vous souhaitez en changer.
				</p>
				<p class = "barre" >
					<form method = "POST">
						<table>
							<tr>
								<td><label for = "login">Login :</label></td>
								<td><input type = "text" name = "login" id = "login" size = "25" value="<?php echo $LecInfo["login"]; ?>" required /></td>
							</tr>
							<tr>
								<td><label for = "mdp">Mot de passe :</label></td>
								<td><input type = "password" name = "mdp" id = "mdp" size = "25" value="<?php echo $LecInfo["mdp"]; ?>" required /></td>
							</tr>
							<tr>
								<td><label for = "email" required >Adresse mail :</label></td>
								<td><input type = "text" name = "email" id = "email" size = "35" value="<?php echo $LecInfo["mail"]; ?>"/></td>
							</tr>
						</table>
						<p class = "validation">
							<input class = "ok" type = "submit" name = "btn_envoi" id = "btn_envoi" value = "EXECUTER"/>
							<input class = "stop" type = "reset" value = "ANNULER" />
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
		$marequete = "UPDATE `personne` SET login = '".$_POST['login']."', mdp = '".$_POST['mdp']."', mail = '".$_POST['email']."' where id_pers = ".$_SESSION['idpers'];
		
		if (mysqli_query ($BDD, $marequete))
		{
?>
			<script>alert("Les paramètres de votre compte ont bien été modifiés");</script>
<?php
		}
	}
}
?>