jQuery(document).ready(function($){
	
	/*
	$('.formline input').focusin(function(){
		$(this).parent().addClass('active');
	}).focusout(function(){
		$(this).parent().removeClass('active');
	});*/
	
	$('.uploadbtn').click(function(e){
		e.preventDefault();
		var elem = $(this);
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
	        elem.parent().find('input.uploadUrl').val(image_url);
	        //elem.parent().find('input.uploadId').val(id);
	    });
	});
	
	$('.socialadd').click(function(e){
	  e.preventDefault();
	  var profile = $('.socialselect').val(),
	      label = $('.socialselect option:selected').text();
	      
	  if($('.socialmediaprofiles .'+profile).length == 0){
  	   var output = '<div class="profiles"><div class="half"><div class="formline small '+profile+'"><label>'+label+'</label><input type="text" name="social['+profile+']" /></div><button class="removesocial">Remove</button></div></div>';
	     $(output).appendTo($('.socialmediaprofiles'));
	  }
	});
	
	$(document).on('click','button.removesocial',function(e){
	  e.preventDefault();
	  var conf = confirm('Really delete profile?');
	  if(conf){
	   $(this).parents('.profiles').remove();
	  }
	});

	$('.colorSelector').each(function(i,item){
		var item = $(item),
			input = item.prev(),
			color = input.val(),
			div = item.find('div');
			div.css('backgroundColor', color);
		item.ColorPicker({
			color: color,
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				div.css('backgroundColor', '#' + hex);
				input.val('#' + hex);
			}
		});
	});
});
