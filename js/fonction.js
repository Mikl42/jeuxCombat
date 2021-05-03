/* 
 * fonction Js
 * 
 */

//--------------------  Fonction augmentation caracteristique----------------------------//

//lors du click sur le bouton augmenter force, on appel le controler 
//augmenter_stat.php en passaant par ajax
// si success alors on modifie le template plateauJeu
//error si un pbl est survenu durant le procedé

//lors du click sur le bouton augmenter resistance, on appel le controler 
//augmenter_stat.php en passaant par ajax
// si success alors on modifie le template plateauJeu
//error si un pbl est survenu durant le procedé



function augmenterStrength(id) {
    //role : declanche la modification des caracteristique et l'affichage sur le template 
    //parametre :
    //  id : id du player
    //retour : affichage  success ou error


    $.ajax("augmenter_force.php?id=" + id, {
        methode: "GET",
        success: changeStat,
        error: function () {
            alert("Une erreur s'est produite");
        }
    });
}
function augmenterRes(id) {
    //role : declanche la modification des caracteristique et l'affichage sur le template 
    //parametre :
    //  id : id du player


    $.ajax("augmenter_res.php?id=" + id, {
        methode: "GET",
        success: changeStat,
        error: function () {
            alert("Une erreur s'est produite");
        }
    });
}


function changeStat(data) {
    //recup la zones a modifié
    var stat = $(".player");
//ajoute le nouveau contenu dans les zones
        stat.html(data);
    
}

function previousRoom(id){
    //role :declanche la modifi de l'affichage du fragments tableauJeu
    //parametre : id du player
    //return : success ou error
    $.ajax("reculer.php?id=" + id, {
        methode: "GET",
        success: backToPreviousRoom,
        error: function () {
            alert("Une erreur s'est produite");
        }
    });
    
}

function backToPreviousRoom(data){
    //role : recupere la zone de modification
    //parametre data : donnée a modifier
    
    var room =$(".room");
    room.html(data);
    
}