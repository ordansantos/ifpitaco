/**
 * Chamado por:
 * 	cadastrar.php
 */


//Form dinâmico
function option1Click() {
    $('#professor').hide();
    $('#aluno').show();
}

function option2Click() {
    $('#aluno').hide();
    $('#professor').show();
}

function option3Click() {
    $('#professor').hide();
    $('#aluno').hide();
}

(function () {
    $(document).ready(function () {
        $('#pop').focusin(function () {
            $('#pop').popover('show');
        });

        $('#pop').focusout(function () {
            $('#pop').popover('hide');
        });

        $('#professor').hide();
        $('[data-toggle="popover"]').popover();
        $('#professor').hide();
    });
})();