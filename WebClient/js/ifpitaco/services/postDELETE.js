/**
 * 
 */

/*Sistema para excluir uma publicação*/

function postDELETE(sender) {

    var e = sender.parentNode.parentNode.parentNode.parentNode;
    var id = e.getAttribute('id');

    bootbox.dialog({message: "Deseja excluir sua publicação?",
        buttons: {
            "Não": {className: "btn-default btn-sm"},
            "Sim": {
                className: "btn-primary btn-sm",
                callback: function () {

                    $.param({post_id: id});
                    $.post("services/deletarPost.php", {post_id: id}, function(data){
                        
                        data = $.parseJSON(data);
                        
                        if (data.status === "unauthorized") {
                            bootbox.alert("Faça login para continuar!", function () {
                                location.reload();
                            });
                        } else{
                            $('#' + id).remove();
                        }
                    });
                    
                }
            }

        }
    });

}