jQuery(document).on('click', '.img_field', function(e) {



var clicked_field = e.target.name;


var custom_uploader;


    e.preventDefault();

    //If the uploader object has already been created, reopen the dialog
    if (custom_uploader) {
        custom_uploader.open();
        return;
    }

    //Extend the wp.media object
    custom_uploader = wp.media.frames.file_frame = wp.media({
        title: 'Select Image',
        button: {
            text: 'Select Image'
        },
        multiple: false
    });

    //When a file is selected, grab the URL and set it as the text field's value
    custom_uploader.on('select', function() {
        attachment = custom_uploader.state().get('selection').first().toJSON();
        jQuery('input[name="'+ clicked_field +'"]').val(attachment.url);
        jQuery('.custom_preview_image').attr('src', attachment.url);
        jQuery('.custom_media_image').attr('src',attachment.url);
    });

    //Open the uploader dialog
    custom_uploader.open();

});


jQuery('.repeatable-add').click(function() {
    field = jQuery(this).closest('td').find('.custom_repeatable li:last').clone(true);
    fieldLocation = jQuery(this).closest('td').find('.custom_repeatable li:last');
    jQuery('input', field).val('').attr('name', function(index, name) {
        return name.replace(/(\d+)/, function(fullMatch, n) {
            return Number(n) + 1;
        });
    })
    field.insertAfter(fieldLocation, jQuery(this).closest('td'))
    return false;
});

jQuery('.repeatable-remove').click(function(){
    jQuery(this).parent().remove();
    return false;
});

jQuery('.custom_repeatable').sortable({
    opacity: 0.6,
    revert: true,
    cursor: 'move',
    handle: '.sort'
});