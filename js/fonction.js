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

$(function () {
    setInterval(timerInterval, 500);


    function timerInterval() {
        $.ajax("actualiser_plateauJeu.php", {
            methode: "GET",
            success: actuPlateau,
            error: function () {
                alert("Une erreur s'est produite.... again");
            }
        });
    }

    function actuPlateau(data) {
        var plateau = $(".putaindeMain");
        plateau.html(data);
    }

});


function augmenterStrength() {
    //role : modification de la force et l'affichage sur le template 
    //parametre :
    //  id : id du player
    //retour : affichage  success ou error


    $.ajax("augmenter_force.php", {
        methode: "GET",
        success: changeStat,
        error: function () {
            alert("Une erreur s'est produite force");
        }
    });

}
function augmenterRes() {
    //role : declanche la modification des caracteristique et l'affichage sur le template 
    //parametre :
    //  id : id du player


    $.ajax("augmenter_res.php", {
        methode: "GET",
        success: changeStat,
        error: function () {
            alert("Une erreur s'est produite resistance");
        }
    });
}


function changeStat(data) {
    //recup la zones a modifié
    var stat = $(".player");
//ajoute le nouveau contenu dans les zones
    stat.html(data);

}


//----------------FUNCTION avancer ou reculer d'une piece------------------
//-------------------------------------------------------------------------

function previousRoom() {
    //role :declanche la modifi de l'affichage du fragments tableauJeu
    //parametre : id du player
    //return : success ou error
    $.ajax("reculer.php", {
        methode: "GET",
        success: backToPreviousRoom,
        error: function () {
            alert("Une erreur s'est produite room previous");
        }
    });

}

function backToPreviousRoom(data) {
    //role : recupere la zone de modification
    //parametre data : donnée a modifier

    var room = $(".roomPrincipal");
    room.html(data);

}

function nextRoom() {
    //role :declanche la modifi de l'affichage du fragments tableauJeu
    //parametre : id du player
    //return : success ou error
    $.ajax("avancer.php", {
        methode: "GET",
        success: goToNextRoom,
        error: function () {
            alert("Une erreur s'est produite room next");
        }
    });

}

function goToNextRoom(data) {
    var room = $(".roomPrincipal");
    room.html(data);
    
}






