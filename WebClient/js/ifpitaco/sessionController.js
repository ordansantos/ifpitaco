/**
 * 
 */

(function(){
    $.ajax({url:'services/updateLastAccess.php'});
    
    $(document).ready(function() {
            setInterval(function () { $.ajax({url:'services/updateLastAccess.php'}); }, 1000 * 60);
    });
    
})();

function Session (idUsuario){
	
	var session = {};
	session.idUsuario = idUsuario;
	
	return {
		getUsuarioId: function(){
			return session.idUsuario;
		}
	};
	
}