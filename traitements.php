<?php 
	include("include/config.php");
	try {
		$bdd = new PDO("mysql:host=".$host.";dbname=".$dbname.";charset=utf8", $user, $pass);
	} catch (PDOException $e) {
		// s'il y a une erreur je la stocke dans ma variable
	    $msgKO .= "Erreur !: " . $e->getMessage() . "<br/>";
	}

// Suppression
if(isset($_GET['delete']) && !empty($_GET['delete'])){
	// Je créé 2 requetes préparées pour supprimer les lignes associant le jeu et les plateformes puis le jeu
	$delete = $bdd->prepare("DELETE FROM `JeuxPlateforme` WHERE `Jeux_Id` = ?; DELETE FROM `Jeux` WHERE `Jeux_Id` = ?;");
	// Je renseigne les éléments de ma requete préparée
	// l'index est important lorsque vous utilisez les ?
	$delete->bindValue("1", $_GET['delete']);
	$delete->bindValue("2", $_GET['delete']);
	// Si tous se passe bien mon msg sera ok
	if($delete->execute()){
		$msg = "ok";
	} else {
		// Sinon ce sera KO
		$msg = "ko";
	}
}

// Modification avec double verification
// Si l'id du jeux est renseigné et si l'action est bien update
if(isset($_POST['Jeux_Id']) && !empty($_POST['Jeux_Id']) && isset($_POST['action']) && $_POST['action']=="update"){
	//print_r($_POST);
	// je créé une requête préparée de modification
	$update = $bdd->prepare("UPDATE `Jeux` SET `Jeux_Titre` = :Jeux_Titre, `Jeux_Description` = :Jeux_Description, `Jeux_Prix` = :Jeux_Prix, `Jeux_DateSortie` = :Jeux_DateSortie, `Jeux_PaysOrigine` = :Jeux_PaysOrigine, `Jeux_Connexion` = :Jeux_Connexion, `Jeux_Mode` = :Jeux_Mode, `Genre_Id` = :Genre_Id WHERE `Jeux`.`Jeux_Id` = :Jeux_Id; ");
	// étant donné que j'ai nommé mes champs input comme mes champs de table, je n'ai plus qu'a parcourir mon tableau $_POST pour inserer les bonnes valeurs
	foreach ($_POST as $key => $value) {
		# code...
		// J'ignore le champ action car pas util dans ma requete et plateformes car il s'agit d'un tableau pas d'un String
		if($key != "Plateformes" && $key != "action")
			$update->bindValue(":".$key, $value);
	}
	// Je supprime toutes les associations jeux/plateformes pour les recréer plus tard
	$updatePlateForme = $bdd->prepare("DELETE FROM `JeuxPlateforme` WHERE `Jeux_Id` = :DelJeux_Id;");
	$updatePlateForme->bindValue(":DelJeux_Id", $_POST['Jeux_Id']);

	//je créé une requête sql pour insérer les bonnes associations
	$sql = "";
	if(isset($_POST['Plateformes']) && !empty($_POST['Plateformes'])) {
		$sql = "INSERT INTO `JeuxPlateforme` (`Jeux_Id`, `Plateforme_Id`) VALUES ";
		// pour chaque plateforme cochée, je rajoute une association
		foreach ($_POST['Plateformes'] as $key => $value) {
			# code...
			$sql .= "('".$_POST['Jeux_Id']."', '".$value."'), ";
		}
		// J'enlève le dernier ", " pour que la requête soit bien formée
		$sql = substr($sql, 0, -2);
	}
	//echo $sql;
	// si update et la suppression des plateformes se passent bien
	if($update->execute() && $updatePlateForme->execute() ){
		// je lance l'insertion des plateformes
		$bdd->exec($sql);
		$msg = "ok";
	} else {
		$msg = "ko";
	}
}
// Insertion avec double verification
// Si l'id du jeux n'existe pas et si l'action est bien insert
if(!isset($_POST['Jeux_Id']) && isset($_POST['action']) && $_POST['action']=="insert"){
	// je créé une requête préparée de modification
	$insert = $bdd->prepare("INSERT INTO `Jeux` (`Jeux_Titre`, `Jeux_Description`, `Jeux_Prix`, `Jeux_DateSortie`, `Jeux_PaysOrigine`, `Jeux_Connexion`, `Jeux_Mode`, `Genre_Id`) VALUES ( :Jeux_Titre, :Jeux_Description, :Jeux_Prix, :Jeux_DateSortie, :Jeux_PaysOrigine, :Jeux_Connexion, :Jeux_Mode, :Genre_Id);");
	// étant donné que j'ai nommé mes champs input comme mes champs de table, je n'ai plus qu'a parcourir mon tableau $_POST pour inserer les bonnes valeurs
	foreach ($_POST as $key => $value) {
		# code...
		// J'ignore le champ action car pas util dans ma requete et plateformes car il s'agit d'un tableau pas d'un String
		if($key != "Plateformes" && $key != "action")
			$insert->bindValue(":".$key, $value);
	}

	if($insert->execute()){

		//je créé une requête sql pour récupérer l'id du jeux qui viens d'être créé
		$lastInsert = $bdd->query("SELECT `Jeux_Id` FROM `Jeux` ORDER BY `Jeux_Id` DESC LIMIT 1;")->fetch();
		$lastInsert= $lastInsert['Jeux_Id'];

		//$lastInsert = $bdd->lastInsertId();
		//je créé une requête sql pour insérer les bonnes associations
		$sql = "";
		if(isset($_POST['Plateformes']) && !empty($_POST['Plateformes'])) {
			$sql = "INSERT INTO `JeuxPlateforme` (`Jeux_Id`, `Plateforme_Id`) VALUES ";
			// pour chaque plateforme cochée, je rajoute une association
			foreach ($_POST['Plateformes'] as $key => $value) {
				# code...
				$sql .= "('".$lastInsert."', '".$value."'), ";
			}
			// J'enlève le dernier ", " pour que la requête soit bien formée
			$sql = substr($sql, 0, -2);
		}
		// je lance l'insertion des plateformes
		$bdd->exec($sql);
		$msg = "ok";
	} else {
		$msg = "ko";
	}
}


header("Location: index.php?msg=".$msg);
exit;