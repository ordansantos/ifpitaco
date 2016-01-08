/**
 * 
 */

//Enviando um novo comentário
function comentarioPOST(id) {
    
    event.preventDefault();
    
    if ($("#input" + id)[0].value == '') {
        return;
    }

    var data = $('#' + id).serializeArray();
    data.push({name: 'post_id', value: id});
    $.post("services/comentar.php", data, function (data) {

        data = $.parseJSON(data);

        if (data.status === "unauthorized") {
            bootbox.alert("Faça login para continuar!", function () {
                location.reload();
            });
        }
        
        COMENTARIO.load(id);
        
    });
    document.getElementById(id).reset();
}