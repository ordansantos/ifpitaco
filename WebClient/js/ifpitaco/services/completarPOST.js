/**
 * Chamado por:
 * 	cadastrar.php
 */

// Enviando os dados ao servidor
(function () {

    $(document).ready(function () {
        $("#form").submit(function (event) {
            event.preventDefault();

            $('#submit').attr("disabled", "disabled");
            var formData = new FormData($(this)[0]);
            $.ajax({
                type: "POST",
                url: "services/completar.php",
                contentType: false,
                processData: false,
                data: formData,
                success: function (data) {
                    
                    data = $.parseJSON(data);
                    
                    if (data.status === "unauthorized"){
                        bootbox.alert("<span style='color: black'>Faça login para continuar!</span>", function () {
                            window.location.assign('index.php');
                        });
                    }
                    
                    if (data.status !== 'success') {
                        $('#error').hide();
                        document.getElementById("error").innerHTML = data.status;
                        $('#error').fadeIn("fast", function () {
                            $('#submit').attr("disabled", false);
                        });
                        window.scrollBy(0, 200);
                    } else
                        window.location.assign("index.php");
                }, error: function (data) {

                    console.log ("erro fatal");

                }
            });
        });
    });
})();