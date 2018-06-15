jQuery(document).ready(function($){
	$('.delete-col a.sb').click(function(e){
		e.preventDefault();
		r = confirm("Really Delete?");
		elem = $(this);
		var name = elem.data('name');
		if(r == true) {
			$.ajax({
				url: ajaxurl,
				cache: false,
				type : 'POST',
				data:'action=my_delete_sidebar&name=' + name,
			}).done(function() {
			 	elem.parents('tr').fadeOut('fast').remove();
			});
		}
	});
});
