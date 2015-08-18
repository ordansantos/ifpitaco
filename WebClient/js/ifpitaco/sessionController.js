/**
 * 
 */

(function () {
    $.ajax({url: 'services/updateLastAccess.php'});

    $(document).ready(function () {
        setInterval(function () {
            $.ajax({url: 'services/updateLastAccess.php'});
        }, 1000 * 60);
    });

})();

function Session(idUsuario, grupo) {

    var session = {};
    session.idUsuario = idUsuario;
    session.grupo = grupo;
    
    return {
        getUsuarioId: function () {
            return session.idUsuario;
        },
        getGrupo: function () {
            return session.grupo;
        }
    };

}