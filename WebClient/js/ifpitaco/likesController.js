/**
 * 
 */

var LAIKES = (function () {

    //Carregando novos 'laikes'

    var laikes = {};

    laikes.load = function (post_idx) {

        $.ajax({
            type: 'POST',
            url: 'services/getLaikes.php',
            data: {post_id: post_idx},
            dataType: 'json',
            cache: false,
            success: function (data) {

                var title = "Laikar";
                $("#nl" + post_idx).attr('class', '');

                if (data.flag == '1') {
                    title = "disLaikar";
                    $("#nl" + post_idx).attr('class', 'laikou');
                }

                $("#nl" + post_idx).siblings('span').html(" " + data.cnt);

                $("#nl" + post_idx)
                        .attr('data-original-title', title)
                        .tooltip('fixTitle');

            },
            error: function (data) {
            }
        });

    };

    return laikes;

})();