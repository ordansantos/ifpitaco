/**
 * Corta uma imagem e seta os atributos do form
 * Chamado por:
 * 	cadastrar.php
 * Atributos:
 *  #crop_x
 *  #crop_y
 *  #crop_w
 *  #crop_h
 *  #img_to_crop
 *  #image_input
 *  
 *  Importar:
 *      <!-- Cropper Plugin-->
 <link  href="js/cropper-master/dist/cropper.css" rel="stylesheet">
 <script src="js/cropper-master/dist/cropper.js"></script>
 */

(function () {

    //Atualiza as coordenadas da imagem no form
    function updateCoords(photo, canvas)
    {
        var percent_x = (photo.left - canvas.left) / canvas.width;
        var percent_y = (photo.top - canvas.top) / canvas.height;
        var percent_width = photo.width / canvas.width;
        var percent_height = photo.height / canvas.height;
        $('#crop_x').val(percent_x);
        $('#crop_y').val(percent_y);
        $('#crop_w').val(percent_width);
        $('#crop_h').val(percent_height);
    }
    ;

    //Mostra a imagem no modal
    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#img_to_crop').attr('src', e.target.result);
                $('#img_to_crop').attr('style', "max-width: 100%");
            };

            reader.readAsDataURL(input.files[0]);
        }
        
        settar();
    }

    function settar() {
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
            updateCoords(cropBoxData, canvasData);
        });
        
    };
    
    $(document).ready(function () {
        //Ao trocar o input
        $("#image_input").change(function () {
            readURL(this);
            $('#cropper-modal').modal('show');
        });

        //Ao clicar: possibilita a troca pela mesma imagem
        $("#image_input").click(function () {
            $(this).attr("value", "");
        });

    });

})();