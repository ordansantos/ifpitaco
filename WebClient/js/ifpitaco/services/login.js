
/**
 * Realiza o login 
 */


(function (){
	$(document).ready(function() {

		$("#form").submit (function(event){

			event.preventDefault();
			$('#login').attr ("disabled", "disabled");

			var values = $(this).serialize();
			
			$.ajax({		
				type: "POST",
				url: "services/login.php",
				data : values,
				success: function (data){
					if ($.trim(data) != '0')
						window.location.assign("home.php");
					else
						errorAnimation();
				},
				error: function (data){
					console.log (data);					
				}
			});

		});
		
		function errorAnimation(){
			$('#error').hide();
			$('#error').fadeIn("fast", function (){
				$('#login').attr ("disabled", false);
			});
		}
		
	});
})();
