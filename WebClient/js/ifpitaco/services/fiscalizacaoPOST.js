/**
 * 
 */


function fiscalizacaoPOST() {

    var formData = new FormData($("#form_fiscalizacao")[0]);
    $.ajax({
        type: "POST",
        url: "services/fiscalizar.php",
        contentType: false,
        processData: false,
        data: formData,
        success: function (data) {
            POST.newPost();
            console.log (data);
        }, error: function (data) {

        }
    });

    POSTFORM.resetFiscalizacao();
}
