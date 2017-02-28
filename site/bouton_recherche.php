			<p class="bouton_recherche">
				<form action="recherche.php" method="POST">
					<input type="text" placeholder="Chercher un dispositif..." name="recherche" value="<?php echo (isset($_POST["recherche"]))?$_POST["recherche"]:""; ?>" />
					<input type="submit" value="Go !" />
				</form>
			</p>