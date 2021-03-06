/**
 * 
 */

function enquetePost() {

    if (!ENQUETEFORM.check())
        return;

    var formData = new FormData($("#form_new_enquete")[0]);
    $.ajax({
        type: "POST",
        url: "services/enquete.php",
        contentType: false,
        processData: false,
        data: formData,
        success: function (data) {
           
            data = $.parseJSON(data);
                        
            if (data.status === "unauthorized") {
                bootbox.alert("Faça login para continuar!", function () {
                    location.reload();
                });
            } else{
                ENQUETE.getEnquete();
            }

        }, error: function (data) {
              
        }
    });

    ENQUETE.reset();
}