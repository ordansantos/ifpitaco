/**
 * 
 */

/*Sistema para excluir um comentário*/
function comentarioDELETE(id) {

    id = id.replace('c', '');

    bootbox.dialog({message: "Deseja excluir seu comentário?",
        buttons: {
            "Não": {className: "btn-default btn-sm"},
            "Sim": {
                className: "btn-primary btn-sm",
                callback: function () {

                    $('#c' + id).remove();
                    $.param({comentario_post_id: id});
                    $.post("services/deletarComentario.php", {comentario_post_id: id});

                }
            }

        }

    });
}