/**
 * 
 */

ENQUETEFORM = (function () {

    var enqueteform = {};

    $(document).ready(function () {

        enqueteform.resetNewEnquete();

        $("#img_input_new_enquete").change(function () {
            enqueteform.loadImageEnqueteForm(this);
        });

    });

    enqueteform.qtd_opt = 2;

    enqueteform.more = function () {
        if (enqueteform.qtd_opt == 2)
            $('#less').show();
        enqueteform.qtd_opt++;

        var form = "<input type='text' class='form-control' name='opt_" + enqueteform.qtd_opt + "' id='opt_" + enqueteform.qtd_opt + "' placeholder='" + enqueteform.qtd_opt + "ª Opção'>";

        var li = document.createElement('li');
        li.id = "l_" + enqueteform.qtd_opt;
        li.innerHTML = form;
        document.getElementById('lista').appendChild(li);

        $('#qtd_opt').val(enqueteform.qtd_opt);

        if (enqueteform.qtd_opt == 5)
            $('#more').hide();

    };

    enqueteform.less = function () {

        if (enqueteform.qtd_opt == 5)
            $('#more').show();

        $('#opt_' + enqueteform.qtd_opt).remove();
        $('#l_' + enqueteform.qtd_opt).remove();
        enqueteform.qtd_opt--;

        $('#qtd_opt').val(enqueteform.qtd_opt);

        if (enqueteform.qtd_opt == 2)
            $('#less').hide();
    };

    enqueteform.resetNewEnquete = function () {

        $("#form_new_enquete")[0].reset();
        for (var i = enqueteform.qtd_opt; i > 2; i--) {
            $('#opt_' + i).remove();
            $('#l_' + i).remove();
        }
        enqueteform.qtd_opt = 2;
        $("#img_new_enquete").hide();
        $('#less').hide();
        $('#more').show();
    };

    enqueteform.check = function () {

        if (document.getElementById('titulo').value == '') {
            bootbox.alert("<h4><strong>Dê um título à sua enquete!</strong></h4>");
            return false;
        }

        for (var i = 1; i <= enqueteform.qtd_opt; i++) {
            if (document.getElementById('opt_' + i).value == '') {
                bootbox.alert("<h4><strong>Preencha todas as opções de voto!</strong></h4>");
                return false;
            }
        }

        return true;

    };

    enqueteform.loadImageEnqueteForm = function (input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {

                $('#img_new_enquete').attr('src', e.target.result);
                $("#img_new_enquete").fadeIn("slow");
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    return enqueteform;
})();