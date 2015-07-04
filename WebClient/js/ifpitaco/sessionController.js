/**
 * 
 */


function Session (idUsuario){
	
	var session = {};
	
	session.idUsuario = idUsuario;
	
	return {
		getUsuarioId: function(){
			return session.idUsuario;
		}
	};
	
}