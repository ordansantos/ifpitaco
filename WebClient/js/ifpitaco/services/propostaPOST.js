/**
 * 
 */


function propostaPOST() {
		
	var values = $('#form_proposta').serialize();
	
	$.ajax({
		url: "services/propor.php",
		type: "post",
		data: values,
		async: false,
		success: function(data){
			if ($.trim(data)!='1')
				alert ("Faça login antes de propor!");
			else
				document.getElementById("comentariop").value = "";
			POST.newPost();
			
		},
		error:function(){
			alert("error");
		}
	});
	
	POSTFORM.resetProposta();
	
}



