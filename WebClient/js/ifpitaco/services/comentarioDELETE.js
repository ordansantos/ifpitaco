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

                    
                    $.param({comentario_post_id: id});
                    $.post("services/deletarComentario.php", {comentario_post_id: id}, function(data){
                        data = $.parseJSON(data);

                        if (data.status === "unauthorized") {
                            bootbox.alert("Faça login para continuar!", function () {
                                location.reload();
                            });
                            
                        }else
                            $('#c' + id).remove();
                    });
                    
                }
            }

        }

    });
}