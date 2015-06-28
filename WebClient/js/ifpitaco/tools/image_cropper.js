/**
 * Corta uma imagem e seta os atributos do form
 * Chamado por:
 * 	cadastrar.php
 */

(function (){
	
	//Atualiza as coordenadas da imagem no form
	function updateCoords(photo, canvas)
	{
	    $('#crop_x').val(photo.left/canvas.width);
	    $('#crop_y').val(photo.top/canvas.height);
	    $('#crop_w').val(photo.width/canvas.width);
	    $('#crop_h').val(photo.height/canvas.height);
	};
	
	//Mostra a imagem no modal
	function readURL(input) {
	
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	
	        reader.onload = function (e) {
	            $('#img_to_crop').attr('src', e.target.result);
	            $('#img_to_crop').attr('style', "max-width: 100%");
	        }
	
	        reader.readAsDataURL(input.files[0]);
	    }
	}
	
	$(document).ready(function(){
		
		//Cropper
		var $image = $('#img_to_crop'),
		    cropBoxData,
		    canvasData;
	
			$('#cropper-modal').on('shown.bs.modal', function () {
	
				$image.cropper({
					
					background: false,
					aspectRatio: 1,
					zoomable: false,
					built: function () {
	
						// Strict mode: set crop box data first
						$image.cropper('setCropBoxData', cropBoxData);
						$image.cropper('setCanvasData', canvasData);
					}
				});
			}).on('hidden.bs.modal', function () {
				cropBoxData = $image.cropper('getCropBoxData');
				canvasData = $image.cropper('getCanvasData');
				$image.cropper('destroy');
				updateCoords (cropBoxData, canvasData);
			});
		
		//Ao trocar o input
		$("#image_input").change(function(){
		    readURL(this);
		    $('#cropper-modal').modal('show')        
		});
		
		//Ao clicar: possibilita a troca pela mesma imagem
		$("#image_input").click(function(){
		    $(this).attr("value", "");
		});
		
	});
	
})();