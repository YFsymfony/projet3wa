
/*

 Mauvaise pratique , car jquery charge un ecouteur de click sur chaque bouton
 si on a boucoups de bouton btn-danger , il y aurra un impact sur les performances.

$(document).ready(function()

{
    $(".btn-danger").click(function(event)
    {
        var stop = confirm("Etes-vous sur de vouloir supprimer ?");

        if(stop == false)
        {
            event.preventDefault();
        }
    })
});

*/


/* methode de bubuling */

/* ici on part d'un parent avec la class disablable qui est écouté et non écouter tous les boutons */
$(document).ready(function()
    {
        $(".disablable").on("click",".btn-danger",function(event)
            {
                var stop = confirm('Etes-vous sur de vouloir supprimer ?');

                if(stop == false)
                {
                    event.preventDefault();
                    event.stopPropagation();
                }

            }
        )
    }
);




