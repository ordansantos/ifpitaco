/**
 * 
 */

function newEnqueteClick(){
	
	if (!ENQUETEFORM.check()) return;

	var formData = new FormData($("#form_new_enquete")[0]);
	$.ajax({
		type: "POST",
		url: "../WebService/postEnquete",
        contentType: false,
        processData: false,
		data: formData,
		success: function(data){
	
			if (data.trim() != '0'){

				ENQUETECONTROLLER.novaEnqueteForm(data);
			}
				
		}, error: function(data){

		}
	});
	
	ENQUETEFORM.resetNewEnquete();
}