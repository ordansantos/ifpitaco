/**
 * 
 */


function propostaPOST() {

    var values = $('#form_proposta').serialize();
    
    if (document.getElementById("comentariop").value === "")
        return;
    
    $.ajax({
        url: "services/propor.php",
        type: "post",
        data: values,
        async: false,
        success: function (data) {
            
            data = $.parseJSON(data);
            
            if (data.status === "unauthorized"){
                bootbox.alert("Faça login para continuar!", function () {
                    location.reload();
                });
            }
            
            document.getElementById("comentariop").value = "";
            POST.newPost();

        },
        error: function () {
            alert("error");
        }
    });

    POSTFORM.resetProposta();

}



