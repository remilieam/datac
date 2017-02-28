			<p class="bouton_connexion">
<?php
				session_start();
				
				if (empty($_SESSION['connecte']) && empty($_SESSION['idpers']))
				{
?>
				<a href="gestion/page_connexion.php">Connexion</a>
<?php 
				}
				
				else
				{

?>
				<a href="gestion/accueil_gestionnaire.php">Déjà connecté</a>
<?php
				}
?>
			</p>