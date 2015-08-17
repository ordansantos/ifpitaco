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
                console.log (data);
            if (data.trim() != '0') {
              
                ENQUETE.getEnquete();
            }

        }, error: function (data) {
              
        }
    });

    ENQUETE.reset();
}