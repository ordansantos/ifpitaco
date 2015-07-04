/**
 * 
 */

function votoPost(id) {

    event.preventDefault();
    var data = $('#form_enquete').serializeArray();
    data.push({name: 'enquete_id', value: id});

    $.post("services/postVoto.php", data, function (data) {

        if (data.trim() == '0')
            bootbox.alert("Faça login para votar!", function () {
                window.location.assign("index.php");
            });
        else
            ENQUETE.afterPostVoto(id);

    });
}