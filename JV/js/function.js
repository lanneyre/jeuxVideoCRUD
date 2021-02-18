$(document).ready( function () {
    // je met enforme mon tableau
    $('#jeuxVideo').DataTable();

    // pour chaque bouton delete 
    $(".delete").each(function(){
        // je rajoute un evenement de type click
    	$(this).on("click", function(e){
    		// j'annule l'effet du click par défaut
            e.preventDefault();
            // split permet de transformer une chaine de caractère en tableau en fonction d'un séparateur
    		var id = $(this).attr("id").split("delete_");
            // id = array("", "l'id qui m'interesse")
    		if(confirm("Êtes vous certain de vouloir supprimer ce jeux vidéo ?")){
    			window.location.href="traitements.php?delete="+id[1];
    		}
    		
    	});
    });
} );