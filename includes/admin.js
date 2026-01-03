jQuery(document).ready(function($){
    $('.rslclwifw-color-field').wpColorPicker();
    $('#rslclwifw_upload_icon_btn').click(function(e) {
        e.preventDefault();
        var image_frame;
        if(image_frame){ image_frame.open(); }
        image_frame = wp.media({
            title: 'Select Media',
            multiple : false,
            library : { type : 'image' }
        });
        image_frame.on('close',function() {
            var selection =  image_frame.state().get('selection');
            if(selection.length === 0) return;
            var attachment = selection.first().toJSON();
            $('#rslclwifw_main_icon_url').val(attachment.url);
        });
        image_frame.open();
    });
});