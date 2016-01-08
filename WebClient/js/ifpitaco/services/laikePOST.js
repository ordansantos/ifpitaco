/**
 * 
 */
//Envio de 'Laike'
function laikePOST(id) {

    id = id.replace('nl', '');
    $("#nl" + id).tooltip('hide');

    $.post("services/laikar.php", {post_id: id}, function (data) {
        data = $.parseJSON(data);
            
        if (data.status === "unauthorized") {
            bootbox.alert("Faça login para continuar!", function () {
                location.reload();
            });

        } else
            LAIKES.load(id);
    });

}
