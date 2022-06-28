// -----------------------------------------------------
// -----------------    AJAX   --------------------
// -----------------------------------------------------

jQuery(function(jQuery) {
    jQuery('form').on("change keyup input click", '.number', function() {
        if(this.value.match(/[^0-9]/g)){
            this.value = this.value.replace(/[^0-9]/g, "");
        };
    });
    jQuery('#custom_button').click(function () {


        jQuery('.form-control').each(function(){
            if(jQuery(this).val() != ''){
                jQuery('.response').text('Добавлен новый объект недвижимости!');

            }
            else {
                jQuery('.response').text('Пожалуйста, заполните все поля!');

            }

        });

        var titleObject = jQuery("#titleT").val();
        var descObject = jQuery("#descript").val();
        var areaObject = jQuery("#areaQ").val();
        var costObject = jQuery("#costQ").val();
        function check() {
            if (jQuery('#liveQ').is(":checked")) {
                var liveObject = true;
            }
            else {
                var liveObject = false;
            }
            return liveObject;
        };
        var floorObject = jQuery("#floorQ").val();
        var cityObject = jQuery("#cityQ").val();
        var addressObject = jQuery("#titleA").val();
        var typeObject = jQuery("#typeQ").val();

        var file_data = jQuery('#imageQ').prop('files');
        var form_data = new FormData();
        form_data.append('action', 'AjaxOb');
        form_data.append('titleObject', titleObject);
        form_data.append('descObject', descObject);
        form_data.append('areaObject', areaObject);
        form_data.append('costObject', costObject);
        form_data.append('liveObject', check());
        form_data.append('floorObject', floorObject);
        form_data.append('cityObject', cityObject);
        form_data.append('addressObject', addressObject);
        form_data.append('typeObject', typeObject);
        jQuery.each( file_data, function( key, value ){
            form_data.append( key, value );
        });
            jQuery.ajax({
                url: pubajax.ajaxurl,
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function (data) {
                    jQuery('#recent_object .row').html(data);
                    jQuery('.form-control').val('');
                }
            });
         });
});
