
/**
 * Realiza o login 
 * Chamado por:
 * 	index.php
 */


(function () {
    $(document).ready(function () {

        $("#form").submit(function (event) {

            event.preventDefault();
            $('#login').attr("disabled", "disabled");

            var values = $(this).serialize();

            $.ajax({
                type: "POST",
                url: "services/login.php",
                data: values,
                success: function (data) {
                    if ($.trim(data) == '1')
                        window.location.assign("home.php");
                    else{
                      
                        $("#error").text(data);
                        errorAnimation();
                        
                    }
                },
                error: function (data) {
                    console.log("erro fatal");
                }
            });

        });

        function errorAnimation() {
            $('#error').hide();
            $('#error').fadeIn("fast", function () {
                $('#login').attr("disabled", false);
            });
        }

    });
})();
