/**
 * 
 */

function votoPost(id) {

    event.preventDefault();
    var data = $('#form_enquete').serializeArray();
    data.push({name: 'enquete_id', value: id});

    $.post("services/postVoto.php", data, function (data) {
        
        data = $.parseJSON(data);

        if (data.status === "unauthorized") {
            bootbox.alert("Faça login para continuar!", function () {
                location.reload();
            });
        } else{
            ENQUETE.voted();
        }

    });
}