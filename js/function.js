$(document).ready( function () {
    // je met en forme mon tableau
    var jv = $('#jeuxVideo').DataTable();
    // pour chaque bouton delete 
    function deleteJeu(){
        $(".delete").each(function(){
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
    }
    
	deleteJeu();    

    jv.on( 'draw', function () {
        deleteJeu();
    } );
} );