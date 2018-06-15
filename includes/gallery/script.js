jQuery(document).ready(function($){
	
	$('.delete-col a.gal').click(function(e){
		e.preventDefault();
		r = confirm("Really Delete?");
		elem = $(this);
		var id = elem.data('id');
		if(r == true) {
			$.ajax({
				url: ajaxurl,
				cache: false,
				type : 'POST',
				data:'action=my_delete_gal&id=' + id,
			}).done(function() {
			 	elem.parents('tr').fadeOut('fast').remove();
			});
		}
	});
	
	$('.deleteImage').click(function(e){
		e.preventDefault();
		r = confirm("Really Delete?");
		elem = $(this);
		var id = elem.data('id'),
		gal_id = elem.data('galid');
		if(r == true) {
			$.ajax({
				url: ajaxurl,
				cache: false,
				type : 'POST',
				data:'action=my_delete_image&id=' + id + '&gal='+gal_id,
			}).done(function() {
				window.location = '/wp-admin/admin.php?page=gallerypage&action=edit&id='+gal_id;
			});
		}
	});
	
	$('.ui-sortable').sortable({
      placeholder: "ui-state-highlight"
    });
	
	if($('#upload-btn').length > 0){
		var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            
            var image_url = uploaded_image.toJSON().url,
            	id = uploaded_image.id;
            // Let's assign the url value to the input field
            image_url = image_url.replace(window.location.origin,'');
            
            $('#image_url').val(image_url);
            $('.helper-tool #set-src').val(image_url).focus().blur();
            $('#img_id').val(id);
            
            $('.helperTool').fadeIn('fast');
            
        });
	}
	
	// Upload Image
    $('#upload-btn').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            
            var image_url = uploaded_image.toJSON().url,
            	id = uploaded_image.id;
            // Let's assign the url value to the input field
            image_url = image_url.replace(window.location.origin,'');
            
            $('#image_url').val(image_url);
            $('.helper-tool #set-src').val(image_url).focus().blur();
            $('#img_id').val(id);
            
            $('.helperTool').fadeIn('fast');
            
        });
    });
    
    // Save Image
    /*$('#saveImage').click(function(e){
    	e.preventDefault();
    	var img = $('#set-src').val().replace(window.location.origin,''),
    		data = $('#data-attr').val()
    });*/
});