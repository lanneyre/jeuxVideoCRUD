<?php 
	//j'insere mes données de configurations 
	include("include/config.php");

	try {
		// Je créé ma connexion vers le serveur mySql
		$bdd = new PDO("mysql:host=".$host.";dbname=".$dbname.";charset=utf8", $user, $pass);
		//je récupère déjà les données dont j'ai besoin à savoir tous les jeux video, tous les genres et toutes les plateformes
		$queryJV = $bdd->query("SELECT * FROM `Jeux` JOIN `Genre` ON `Jeux`.`Genre_Id` = `Genre`.`Genre_Id` ORDER BY `Jeux_Titre` ASC;");
		$queryGenre = $bdd->query("SELECT * FROM `Genre` ORDER BY `Genre_Titre` ASC");
		$queryPlateforme = $bdd->query("SELECT * FROM `Plateforme` ORDER BY `Plateforme_Nom` ASC");
	} catch (PDOException $e) {
		// s'il y a une erreur je la stocke dans ma variable
	    $msgKO .= "Erreur !: " . $e->getMessage() . "<br/>";
	}
	


?>
<!doctype html>
<html lang="fr">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <!-- DataTable CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.css"/>
    <!-- font awesome CSS icons -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
	<!-- style perso -->
    <link rel="stylesheet" href="css/style.css">

    <title>CRUD</title>
  </head>
  <body>
    <section class="container" id="wrapper">
    	<!-- Affichage des messages d'erreurs -->
    	<?php if(!empty($msgKO)) {
			// permet d'afficher les msg d'erreurs
			?>
		<div class="alert alert-danger" role="alert">
		  <?php echo $msgKO; ?>
		</div>
			<?php
		} ?>
		<!-- Affichage des messages de succés -->
    	<?php if(!empty($msgOK)) {
    		// permet d'afficher les msg de succès
			?>
		<div class="alert alert-success" role="alert">
		  <?php echo $msgOK; ?>
		</div>
			<?php
		} ?>
		<!-- J'utilise bootstrap avec des formulaires en modal -->
		<div class="row">
			<div class="col-10"><h1>Jeux Vidéo</h1></div>
			<div class="col-2">
				<button class="btn btn-outline-info addButton" data-toggle="modal" data-target="#ModalAdd"><i class="far fa-plus-square"></i></button>
				<!-- Modal -->
				<div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				  <div class="modal-dialog" role="document">
				    <div class="modal-content">
				    	<form name="AddJeuVideo" method="POST" action="traitements.php">
					      <div class="modal-header">
					        <h4 class="modal-title" id="exampleModalLabel">Ajouter un jeu</h4>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body">
					      	
					      		<label for="Jeux_Titre">Titre</label>
					      		<input type="text" name="Jeux_Titre" id="Jeux_Titre" placeholder="Titre" class="form-control">
					      		<label for="Jeux_Description">Description</label>
					      		<textarea name="Jeux_Description" id="Jeux_Description" class="form-control" placeholder="Description"></textarea>
					      		<label for="Jeux_Prix">Prix</label>
					      		<input type="text" name="Jeux_Prix" id="Jeux_Prix" placeholder="Prix" class="form-control">
					      		<label for="Jeux_DateSortie">Date de sortie</label>
					      		<input type="date" name="Jeux_DateSortie" id="Jeux_DateSortie" placeholder="date de sortie" class="form-control">
					      		<label for="Jeux_PaysOrigine">Pays</label>
					      		<input type="text" name="Jeux_PaysOrigine" id="Jeux_PaysOrigine" placeholder="Pays" class="form-control">
					      		<label for="Jeux_Mode">Mode de jeu</label>
					      		<input type="text" name="Jeux_Mode" id="Jeux_Mode" placeholder="Mode de jeu" class="form-control">
					      		<label for="Jeux_Connexion">Connexion</label>
					      		<input type="text" name="Jeux_Connexion" id="Jeux_Connexion" placeholder="Connexion" class="form-control">
								<label for="Genre_Id">Genre</label>
					      		<select name="Genre_Id" id="Genre_Id" class="form-control">
					      			<?php 
					      			// Pour chaque genre de jeux je créé une option dans mon select
					      			while($genre = $queryGenre->fetch()){
					      				?><option value="<?php echo $genre['Genre_Id'];?>"><?php echo $genre['Genre_Titre'];?></option><?php
					      			} ?>
					      		</select>
								Plateformes <br>
								<?php
									// Pour chaque plateforme je créé une case à cocher qui aura le nom de Plateformes[] 
									// L'avantage de donner le même nom avec des [] réside dans le fait que la variable $_POST['Plateformes'] sera un tableau contenant tous les checkbox coché !
									while($Plateforme = $queryPlateforme->fetch()){
					      				?><div class="form-check form-check-inline">
					      					<input class="form-check-input" type="checkbox" name="Plateformes[]" id="Plateforme<?php echo $Plateforme['Plateforme_id'];?>" value="<?php echo $Plateforme['Plateforme_Id'];?>">
					      					<label class="form-check-label" for="Plateforme<?php echo $Plateforme['Plateforme_id'];?>"><?php echo $Plateforme['Plateforme_Nom'];?></label>
					      				</div><?php 
					      			} ?>
					      	
					      </div>
					      <div class="modal-footer">
					      	<input type="hidden" name="action" value="insert">
					        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					        <button type="Submit" class="btn btn-primary">Save data</button>
					      </div>
				      </form>
				    </div>
				  </div>
				</div>
				<!-- Fin Modal -->
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<table class="table table-striped table-hover" id="jeuxVideo">
					<thead>
						<tr>
							<th>Id</th>
							<th>Titre</th>
							<th>Prix</th>
							<th>Date de sortie</th>
							<th>Genre</th>
							<th>Origine</th>
							<th>Mode</th>
							<th>Connexion</th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>Id</th>
							<th>Titre</th>
							<th>Prix</th>
							<th>Date de sortie</th>
							<th>Genre</th>
							<th>Origine</th>
							<th>Mode</th>
							<th>Connexion</th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
					</tfoot>
					<tbody>
						<?php 
						while($jeuxVideo = $queryJV->fetch()){ ?>
						<tr>
							<td><?php echo $jeuxVideo['Jeux_Id'];?></td>
							<td><?php echo $jeuxVideo['Jeux_Titre'];?></td>
							<td><?php echo $jeuxVideo['Jeux_Prix'];?>&euro;</td>
							<td><?php echo $jeuxVideo['Jeux_DateSortie'];?></td>
							<td><?php echo $jeuxVideo['Genre_Titre'];?></td>
							<td><?php echo $jeuxVideo['Jeux_PaysOrigine'];?></td>
							<td><?php echo $jeuxVideo['Jeux_Mode'];?></td>
							<td><?php echo $jeuxVideo['Jeux_Connexion'];?></td>
							<td>
								<button class="btn btn-outline-info btn-block" data-toggle="modal" data-target="#ModalShow<?php echo $jeuxVideo['Jeux_Id'];?>"><i class="far fa-eye"></i></button>
								<!-- Modal -->
								<div class="modal fade" id="ModalShow<?php echo $jeuxVideo['Jeux_Id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
								  <div class="modal-dialog" role="document">
								    <div class="modal-content">
									      <div class="modal-header">
									        <h4 class="modal-title" id="exampleModalLabel"><?php echo $jeuxVideo['Jeux_Titre'];?></h4>
									        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
									          <span aria-hidden="true">&times;</span>
									        </button>
									      </div>
									      <div class="modal-body">
									      		<h4 class="ShowInfo">Description</h4>
									      		<?php echo $jeuxVideo['Jeux_Description'];?><br>
									      		<h4 class="ShowInfo">Prix</h4>
									      		<?php echo $jeuxVideo['Jeux_Prix'];?>&euro;<br>
									      		<h4 class="ShowInfo">Date de sortie</h4>
									      		<?php echo $jeuxVideo['Jeux_DateSortie'];?><br>
									      		<h4 class="ShowInfo">Pays</h4>
									      		<?php echo $jeuxVideo['Jeux_PaysOrigine'];?><br>
									      		<h4 class="ShowInfo">Mode de jeu</h4>
									      		<?php echo $jeuxVideo['Jeux_Mode'];?><br>
									      		<h4 class="ShowInfo">Connexion</h4>
									      		<?php echo $jeuxVideo['Jeux_Connexion'];?><br>
												<h4 class="ShowInfo">Genre</h4>
												<?php echo $jeuxVideo['Genre_Titre'];?><br>
												<h4 class="ShowInfo">Plateformes</h4>
												<?php
													$queryPlateformeJeux = $bdd->query("SELECT * FROM `JeuxPlateforme` WHERE `Jeux_Id` = ".$jeuxVideo['Jeux_Id']);
													$JP = $queryPlateformeJeux->fetchAll(); 
													$JeuxPlateforme = array();
													foreach ($JP as $value) {
														# code...
														$JeuxPlateforme[] = $value["Plateforme_Id"];
													}
													$queryPlateforme = $bdd->query("SELECT * FROM `Plateforme` ORDER BY `Plateforme_Nom` ASC");
													// Pour chaque plateforme j'affiche le nom de Plateformes[] 
									
													while($Plateforme = $queryPlateforme->fetch()){
									      				if(in_array($Plateforme['Plateforme_Id'], $JeuxPlateforme)) echo $Plateforme['Plateforme_Nom']."<br>";
									      			} ?>
									      </div>
									      <div class="modal-footer">
									        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									      </div>
								    </div>
								  </div>
								</div>
								<!-- Fin Modal -->
							</td>
							<td>
								<button class="btn btn-outline-warning btn-block" data-toggle="modal" data-target="#ModalEdit<?php echo $jeuxVideo['Jeux_Id'];?>"><i class="far fa-edit"></i></button>
								<!-- Modal -->
								<div class="modal fade" id="ModalEdit<?php echo $jeuxVideo['Jeux_Id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
								  <div class="modal-dialog" role="document">
								    <div class="modal-content">
								    	<form name="EditJeuVideo" method="POST" action="traitements.php">
									      <div class="modal-header">
									        <h4 class="modal-title" id="exampleModalLabel">Editer un jeu</h4>
									        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
									          <span aria-hidden="true">&times;</span>
									        </button>
									      </div>
									      <div class="modal-body">
									      	
									      		<label for="Jeux_Titre">Titre</label>
									      		<input type="text" name="Jeux_Titre" id="Jeux_Titre" value="<?php echo $jeuxVideo['Jeux_Titre'];?>" class="form-control">
									      		<label for="Jeux_Description">Description</label>
									      		<textarea name="Jeux_Description" id="Jeux_Description" class="form-control" ><?php echo $jeuxVideo['Jeux_Description'];?></textarea>
									      		<label for="Jeux_Prix">Prix</label>
									      		<input type="text" name="Jeux_Prix" id="Jeux_Prix" value="<?php echo $jeuxVideo['Jeux_Prix'];?>" class="form-control">
									      		<label for="Jeux_DateSortie">Date de sortie</label>
									      		<input type="date" name="Jeux_DateSortie" id="Jeux_DateSortie" value="<?php echo $jeuxVideo['Jeux_DateSortie'];?>" class="form-control">
									      		<label for="Jeux_PaysOrigine">Pays</label>
									      		<input type="text" name="Jeux_PaysOrigine" id="Jeux_PaysOrigine" value="<?php echo $jeuxVideo['Jeux_PaysOrigine'];?>" class="form-control">
									      		<label for="Jeux_Mode">Mode de jeu</label>
									      		<input type="text" name="Jeux_Mode" id="Jeux_Mode" value="<?php echo $jeuxVideo['Jeux_Mode'];?>" class="form-control">
									      		<label for="Jeux_Connexion">Connexion</label>
									      		<input type="text" name="Jeux_Connexion" id="Jeux_Connexion" value="<?php echo $jeuxVideo['Jeux_Connexion'];?>" class="form-control">
												<label for="Genre_Id">Genre</label>
									      		<select name="Genre_Id" id="Genre_Id" class="form-control">
									      			<?php
									      				$queryGenre = $bdd->query("SELECT * FROM `Genre` ORDER BY `Genre_Titre` ASC");
									      				// Pour chaque genre de jeux je créé une option dans mon select
									      				while($genre = $queryGenre->fetch()){
									      				?><option value="<?php echo $genre['Genre_Id'];?>" <?php if($jeuxVideo['Genre_Id'] == $genre['Genre_Id']) echo 'selected="selected"'; ?>><?php echo $genre['Genre_Titre'];?></option><?php
									      			} ?>
									      		</select>
												Plateformes <br>
												<?php
													$queryPlateformeJeux = $bdd->query("SELECT * FROM `JeuxPlateforme` WHERE `Jeux_Id` = ".$jeuxVideo['Jeux_Id']);
													$JP = $queryPlateformeJeux->fetchAll(); 
													$JeuxPlateforme = array();
													foreach ($JP as $value) {
														# code...
														$JeuxPlateforme[] = $value["Plateforme_Id"];
													}
													$queryPlateforme = $bdd->query("SELECT * FROM `Plateforme` ORDER BY `Plateforme_Nom` ASC");
													// Pour chaque plateforme je créé une case à cocher qui aura le nom de Plateformes[] 
													// L'avantage de donner le même nom avec des [] réside dans le fait que la variable $_POST['Plateformes'] sera un tableau contenant tous les checkbox coché !
													while($Plateforme = $queryPlateforme->fetch()){
									      				?><div class="form-check form-check-inline">
									      					<input class="form-check-input" type="checkbox" name="Plateformes[]" id="Plateforme<?php echo $Plateforme['Plateforme_Id'];?>" <?php if(in_array($Plateforme['Plateforme_Id'], $JeuxPlateforme)) echo 'checked="checked"'; ?> value="<?php echo $Plateforme['Plateforme_Id'];?>">
									      					<label class="form-check-label" for="Plateforme<?php echo $Plateforme['Plateforme_Id'];?>"><?php echo $Plateforme['Plateforme_Nom'];?></label>
									      				</div><?php 
									      			} ?>
									      </div>
									      <div class="modal-footer">
									      	<!-- Les champs cachés me permettent d'identifier avec certitude quel jeu je vais modifieret qu'il s'agit bien d'un update et pas d'un insert -->
									      	<input type="hidden" name="Jeux_Id" value="<?php echo $jeuxVideo['Jeux_Id'];?>">
									      	<input type="hidden" name="action" value="update">
									        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									        <button type="submit" class="btn btn-primary">Save changes</button>
									      </div>
								      </form>
								    </div>
								  </div>
								</div>
								<!-- Fin Modal -->
							</td>
							<td>
								<button class="btn btn-outline-danger btn-block delete" id="delete_<?php echo $jeuxVideo['Jeux_Id'];?>"><i class="far fa-trash-alt"></i></button>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
    </section>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    <!-- Datatables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.js"></script>
    <!-- JS PERSO -->
    <script type="text/javascript" src="js/function.js"></script>
  </body>
</html>