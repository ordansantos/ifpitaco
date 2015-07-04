

(function () {
    $(document).ready(function () {

        $("#form").submit(function (event) {
            event.preventDefault();

            $('#submit').attr("disabled", "disabled");

            var formData = new FormData($(this)[0]);

            $.ajax({
                type: "POST",
                url: "../WebService/alterarDados",
                contentType: false,
                processData: false,
                data: formData,
                success: function (data) {

                    if ($.trim(data) != '1') {

                    } else
                        window.location.assign("home.php");

                }, error: function (data) {

                    bootbox.alert("Faça o upload corretamente! Escolha uma imagem válida ou uma imagem menor que 5000 x 5000.", function () {
                        window.location.assign('myProfile.php');
                    });

                }
            });
        });


    });

})();