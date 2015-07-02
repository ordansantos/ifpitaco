/**
 * 
 */

(function(){
	
	$(document).ready(function() {
		setInterval(function () {atualizaTempos()}, 1000 * 60);
	});
	
	function atualizaTempos(){
		
		COMENTARIO.atualizaTempo();
		
		
	}
	
})();