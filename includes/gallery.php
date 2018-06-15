<!-- -Gallery -->
<div id="gallery" class="cardWrapper closed">
  <div class="card">
    <div class="cardFace front">
    	<?php 
    	/* GALLERY OUTPUT */
    	
    	global $wpdb;
		$galleryid = get_post_meta( get_the_ID(), '_my_gallery_value_key', true );
		$valid = false;
		if($galleryid) {
			$gallery = $wpdb->get_row( 'SELECT * FROM '.$wpdb->prefix.'galleries where id = '.$galleryid );
			$images = json_decode($gallery->images,true);
			if($images && count($images) > 0){
				$valid = true;
			}
		}
		
		if($valid) {
			$iterator = 0;
			foreach($images as $image){
				$active = '';
				if($iterator == 0) {
					$active = 'active';
				}
				echo '
				<div class="focuspoint galImage '.$active.'" '.$image['data'].'>
					'.wp_get_attachment_image( $image['id'], 'gallery-bg' ).'
				</div>
				';
				$iterator ++;
			}
		} else {
			// Use Post Thumbnail
			if ( has_post_thumbnail()) {
				$img = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID),'gallery-bg' );
				$url = $img[0];
				$w = $img[1];
				$h = $img[2];
				echo '
				<div class="focuspoint galImage active" data-focus-x="0.00" data-focus-y="0.00" data-focus-w="'.$w.'" data-focus-h="'.$h.'">
					<img src="'.$url.'" />
				</div>
				';
			} else {
				// Use default gallery
				$settings = json_decode(get_option('wp_fancy_settings'), true); 
				$galleryid = (isset($settings['default_gal']) && $settings['default_gal'] != '') ? $settings['default_gal'] : false;
				if($galleryid) {
					$gallery = $wpdb->get_row( 'SELECT * FROM '.$wpdb->prefix.'galleries where id = '.$galleryid );
					$images = json_decode($gallery->images,true);
					$iterator = 0;
					foreach($images as $image){
						$active = '';
						if($iterator == 0) {
							$active = 'active';
						}
						echo '
						<div class="focuspoint galImage '.$active.'" '.$image['data'].'>
							'.wp_get_attachment_image( $image['id'], 'gallery-bg' ).'
						</div>
						';
						$iterator ++;
					}
				} else {
					// Use default img
					$default = (isset($settings['galleryImage']) && $settings['galleryImage'] != '') ? $settings['galleryImage'] : '/wp-content/themes/wpfancy/resources/img/default.jpg';
					echo '
					<div class="focuspoint galImage active" data-focus-x="0.00" data-focus-y="0.00" data-focus-w="1780" data-focus-h="777">
						<img src="'.$default.'" />
					</div>
					';
				}
			}
		}
    	
    	/* GALLERY OUTPUT END */
    	?>
    	
    	<div class="navigation">
    		<button class="prevButton"><i class="fa fa-chevron-left"></i></button>
    		<button class="nextButton"><i class="fa fa-chevron-right"></i></button>
    		<button class="closeGallery"><i class="fa fa-times"></i> <?php echo __('Close Gallery', 'wpfancy'); ?></button>
    	</div>
    	<button class="openGallery"><i class="fa fa-camera"></i> <span><?php echo __('Open Gallery', 'wpfancy'); ?></span></button>
    </div>
  </div>
</div>