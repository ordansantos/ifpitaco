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
                url: "../WebService/postUsuario",
                contentType: false,
                processData: false,
                data: formData,
                success: function (data) {

                    if ($.trim(data) != '1') {
                        $('#error').hide();
                        document.getElementById("error").innerHTML = data;
                        $('#error').fadeIn("fast", function () {
                            $('#submit').attr("disabled", false);
                        });
                        window.scrollBy(0, 200);
                    } else
                        window.location.assign("index.php");
                }, error: function (data) {

                    bootbox.alert("Faça o upload corretamente! Escolha uma imagem válida ou uma imagem menor que 5000 x 5000.", function () {
                        window.location.assign('cadastrar.php');
                    });

                }
            });
        });
    });
})();