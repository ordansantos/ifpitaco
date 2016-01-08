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
            
            data = $.parseJSON(data);
            
            if (data.status === "unauthorized"){
                bootbox.alert("Faça login para continuar!", function () {
                    location.reload();
                });
            }
            
            POST.newPost();
    
        }, error: function (data) {

        }
    });

    POSTFORM.resetFiscalizacao();
}
