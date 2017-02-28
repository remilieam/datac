<!doctype html>

<html>
	<head>
		<title>DATÀC – Connexion</title>
		<meta charset = "utf-8" />
		<link rel = "stylesheet" href = "msf_gestionnaire.css"/>
		<link rel = "icon" type = "image/png" href = "../../images/datac_logo.png" />
	</head>
	<body>
		<br/><br/><br/><br/><br/>
		<section> 
<?php
	session_start();
	$connexion = false;
	if (array_key_exists('login', $_POST)) 
	{
?>
			<article class = "erreur">
				<p>  
					<br/><br/>
					Votre login ou votre mot de passe n'est pas correct. 
					<br/>
					Veuillez recommencer votre saisie pour vous connecter
					<br/><br/>
				</p>  
			</article>
			<br/><br/>

<?php
	}
?>
			<article>
				<br/><br/>
				<h2>CONNEXION :</h2>
				<br/>
				<p class = "barre">
					<form method = "post">
						<table> 
							<tr> 
								<td><label for = "login">Login :</label></td> 
								<td><input type = "text" name = "login" id = "login" size = "25" /></td> 
							</tr>
							<tr> 
								<td><label for = "mdp">Mot de passe :</label></td>
								<td><input type = "password" name = "mdp" id = "mdp" size = "25"/></td>
							</tr> 
						</table>
						<p class = "validation"> 
							<input type = "submit"  name = "btn_envoi" id = "btn_envoi" value = "EXECUTER"/>
							<input type = "reset" value = "ANNULER" />
						</p>
					</form>
					<br/><br/><br/> 
				</p>
				<p><a href = "mailto:marie.guiraute@ensc.fr;marwa.mathlouthi@ensc.fr;emilie.roger@ensc.fr;alexia.tartas@ensc.fr?subject=DATÀC – Mot de passe oublié">Mot de passe oublié ?</a></p>  
			</article>
		</section>
<?php
	if (array_key_exists('login', $_POST)) 
	{
		// condition qui vérifie que le login a bien été rentré donc la personne a tenté une connexion
		require ("../connect.php");
		mysqli_set_charset($BDD, "utf8");
		
		// utilisation de la seconde base juste pour la connexion
		$monRS = "SELECT * FROM personne WHERE login = '" . $_POST['login'] . "' and mdp = '" . $_POST['mdp'] . "'";
		$marequete = mysqli_query($BDD, $monRS);
		
		if (empty($connexion)) { $connexion = 1; }
		else {$connexion++;}

		if ($connexion == 3) 
		{
			header('Location: page_attente.php');
			exit();
		}
		
		//test pour les mutliples tentatives de connexion
		while ($tuple = mysqli_fetch_array($marequete))
		{
			$_SESSION['idpers'] = $tuple['id_pers'];
			$_SESSION['statut'] = $tuple['statut'];
			$_SESSION['connecte'] = true;

			header('Location: accueil_gestionnaire.php');
			exit();
		}
	}
?>
	</body>
</html>