/**
 * 
 */


function fiscalizacaoPOST() {

    var formData = new FormData($("#form_fiscalizacao")[0]);
    $.ajax({
        type: "POST",
        url: "../WebService/postFiscalizacao",
        contentType: false,
        processData: false,
        data: formData,
        success: function (data) {
            POST.newPost();
        }, error: function (data) {

        }
    });

    POSTFORM.resetFiscalizacao();
}
