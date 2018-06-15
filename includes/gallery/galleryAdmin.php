<?php
function my_galleryPage(){
	$validActions = array('listview', 'newGal', 'edit', 'editImage', 'addImage', 'deleteGal');
	$action = isset($_GET['action']) ? $_GET['action'] : 'listview';
	if(in_array($action, $validActions)){
		call_user_func($action);
	}
}
function listview(){
	global $wpdb;
	echo '
		<div class="wrap">
	   		<h2>'.__('Galleries', 'wpfancy').' <a href="/wp-admin/admin.php?page=gallerypage&action=newGal" class="add-new-h2">'.__('Create', 'wpfancy').'</a></h2>
		
	';
	$galleryData = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'galleries Order by id' );
	?>
	
	<table class="wp-list-table widefat fixed">
		<thead>
		<tr>
			<th scope="col" class="manage-column sortable column-uid"><a href="#"><?php echo __('Id', 'wpfancy'); ?></a></th>
			<th scope="col" id="title" class="manage-column column-title sortable desc" style="">
				<a href="/wp-admin/edit.php?post_type=page&amp;orderby=title&amp;order=asc"><span><?php echo __('Title', 'wpfancy'); ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th>
				<?php echo __('Images', 'wpfancy') ?>
			</th>
			<th scope="col" id="date" class="manage-column column-date sortable asc" style="">
				<a href="http://fancy.andreknieriem.de/wp-admin/edit.php?post_type=page&amp;orderby=date&amp;order=desc"><span><?php echo __('Date', 'wpfancy'); ?></span><span class="sorting-indicator"></span></a>
			</th>	
			<th class="delete-col"><?php echo __('Actions', 'wpfancy'); ?></th>
		</tr>
		</thead>
		<tbody>
			<?php
			$alternate = 'alternate';
			$count = 0;
			foreach($galleryData as $gallery){
				$alternate = ($count % 2 == 0) ? 'alternate': '';
				echo '
					<tr class="'.$alternate.'">
						<td>'.$gallery->id.'</td>		
						<td><a href="/wp-admin/admin.php?page=gallerypage&action=edit&id='.$gallery->id.'">'.$gallery->name.'</a></td>
						<td>'.count(json_decode($gallery->images,true)).'</td>
						<td>'.date('Y-m-d H:i:s', $gallery->created).'</td>
						<td class="delete-col"><a class="gal" href="#" data-id="'.$gallery->id.'">'.__('Delete', 'wpfancy').'</a></td>					
					</tr>
				';
				$count ++;
			}
			
			?>
		</tbody>		
	</table>
	</div>
	<?php
}

function newGal() {
	global $wpdb;
	if(isset($_POST['name'])) {
		$data['name'] = $_POST['name'];
		$data['images'] = json_encode(array());
		$data['created'] = time();
		$wpdb->insert( $wpdb->prefix.'galleries', $data );
		$id = $wpdb->insert_id;
		$newURL = 'http://'.$_SERVER['HTTP_HOST'].'/wp-admin/admin.php?page=gallerypage&action=edit&new=true&id='.$id;
		echo '<script type="text/javascript">window.location = "'.$newURL.'"</script>';
		die();
	}
	echo '
	<div id="wpfancy_settings">
		<div class="labelRow">
			<strong>'.__('Create Gallery', 'wpfancy').'</strong>
		</div>
   		<form action="" method="post" id="createGalForm">
   		<div class="row">
	   		<div class="half">
		   		<div class="formline">
		   			<label for="name">'.__('Name', 'wpfancy').'</label><br/>
		   			<input class="widefat" type="text" id="name" name="name" />
		   		</div>
	   		</div>
	   		<div class="half"></div>
	   		<div class="clear"></div>
   		</div>
   		<div class="row">
			<div class="leftCol">
			</div>
			<div class="rightCol">
				<input name="save" type="submit" class="pull-right button button-primary button-large" value="'.__('Add Gallery', 'wpfancy').'">
			</div>
			<div class="clear"></div>
		</div>
   		</form>
	</div>
	';
}

function edit() {
	global $wpdb;
	$id = $_GET['id'];
	$new = false;
	if(isset($_GET['new'])){
		$new = true;
	}
	$save = false;
	if(isset($_POST['gal_name'])){
		$g = $wpdb->get_row( 'SELECT * FROM '.$wpdb->prefix.'galleries where id = '.$id );
		$g->images = json_decode($g->images, true);
		$update = array();
		if($g->name != $_POST['gal_name']) {
			$update['name'] = $_POST['gal_name'];
		}
		$sorted = array();
		foreach($_POST['image'] as $image){
			$sorted[$image] = $g->images[$image];
		}
		$update['images'] = json_encode($sorted);
		$wpdb->update(
			$wpdb->prefix.'galleries', 
			$update,
			array('id' => $id)
		);
		
		$save = true;
	}
	
	$gallery = $wpdb->get_row( 'SELECT * FROM '.$wpdb->prefix.'galleries where id = '.$id );
	$gallery->images = json_decode($gallery->images, true);
	
	?>
		<div id="wpfancy_settings">
		<div class="labelRow">
			<strong><?php echo __('Edit Gallery', 'wpfancy'); ?></strong>
		</div>
	   		<?php if($save) {
	   			echo '<div id="message" class="updated below-h2"><p>'.__('Gallery updated', 'wpfancy').'</p></div>';
	   		}?>
	   		<?php if($new) {
	   			echo '<div id="message" class="updated below-h2"><p>'.__('Gallery created', 'wpfancy').'</p></div>';
	   		}?>
			<form method="POST" action="/wp-admin/admin.php?page=gallerypage&action=edit&id=<?php echo $id; ?>">
				<div class="row">
			   		<div class="half">
				   		<div class="formline">
				   			<label for="gal_name"><?php echo __('Name', 'wpfancy'); ?></label>
				   			<input type="text" id="gal_name" name="gal_name" value="<?php echo $gallery->name ?>" />
				   		</div>
			   		</div>
			   		<div class="half"></div>
			   		<div class="clear"></div>
		   		</div>
				<div class="labelRow">
					<strong><?php echo __('Images', 'wpfancy'); ?></strong> <a href="/wp-admin/admin.php?page=gallerypage&action=addImage&gal=<?php echo $id; ?>" class="add-new button-primary"><?php echo __('Add', 'wpfancy'); ?></a>
				</div>
				<div class="row">
					<div class="half"><?php echo __('Drag and drop images to sort them', 'wpfancy'); ?></div>
					<div class="half"></div>
					<div class="clear"></div>
				<div class="galleryData">
				<ul tabindex="-1" class="attachments ui-sortable" id="attachments-view-37">
				<?php
					if(count($gallery->images) > 0) {
						$count = 0;
						foreach($gallery->images as $image) {
							$count ++;	
							echo '
							<li id="img_'.$image['id'].'" tabindex="'.$count.'" role="checkbox" aria-label="test" aria-checked="false" data-id="24" class="attachment save-ready">
								<a href="/wp-admin/admin.php?page=gallerypage&action=editImage&galid='.$id.'&id='.$image['id'].'">
								<div class="attachment-preview js--select-attachment type-image subtype-jpeg landscape">
									<div class="thumbnail">
										<div class="centered">
											<img src="'.wp_get_attachment_image_src( $image['id'], 'medium' )[0].'" />
										</div>
									</div>
								</div>
								</a>
								<input type="hidden" name="image[]" value="'.$image['id'].'"/>
							</li>
							';
						}
					} else {
						echo '<li class="ui-state-highlight">
						<a href="/wp-admin/admin.php?page=gallerypage&action=addImage&gal='.$id.'">
						 '.__('Add image', 'wpfancy').'
						 </a>
						 </li>
						';
					}
				?>
				</ul>
				</div>
				<div class="clear"></div>
				</div>
				<div class="row">
					<div class="leftCol">
					</div>
					<div class="rightCol">
						<input name="save" type="submit" class="pull-right button button-primary button-large" value="<?php echo __('Update Gallery', 'wpfancy'); ?>">
					</div>
					<div class="clear"></div>
				</div>
			</form>
		</div>
		<?php
}

function editImage() {
	global $wpdb;
	$id = $_GET['galid'];
	$img_id = $_GET['id'];
	$gallery = $wpdb->get_row( 'SELECT * FROM '.$wpdb->prefix.'galleries where id = '.$id );
	$image = json_decode($gallery->images,true);
	$image = $image[$img_id];
	
	if(isset($_POST['data-attr']) && $_POST['data-attr'] != ''){
		$gallery = $wpdb->get_row( 'SELECT * FROM '.$wpdb->prefix.'galleries where id = '.$id );
		$images = json_decode($gallery->images, true);
		$images[$_POST['img_id']] = array(
			'id' => $_POST['img_id'],
			'url' => $_POST['set-src'],
			'data' => stripslashes($_POST['data-attr'])
		);
		
		$wpdb->update(
			$wpdb->prefix.'galleries', 
			array('images' => json_encode($images)),
			array('id' => $id)
		);
		
		$newURL = 'http://'.$_SERVER['HTTP_HOST'].'/wp-admin/admin.php?page=gallerypage&action=edit&id='.$id;
		echo '<script type="text/javascript">window.location = "'.$newURL.'"</script>';
		die();
	}
	
	echo '
		<div class="wrap">
	   		<h2>'.__('Edit Image', 'wpfancy').'</h2>
		</div>
		<div id="Info" class="">
			<div class="helper-tool">
				<h1>'.__('Click the image to set the FocusPoint.', 'wpfancy').'</h1>
				<!-- <img class="focus-target-reticle" src="../img/focuspoint-target.png" /> -->
				<div class="helper-tool-target">
					<img class="helper-tool-img" '.$image['data'].' src="'.$image['url'].'">
					<img class="reticle" src="'.get_template_directory_uri().'/includes/gallery/img/focuspoint-target.png">
					<img class="target-overlay" src="'.$image['url'].'">
				</div>
				<form method="post">
				<p class="hidden">
					<label for="set-src">Paste in a URL to change the image</label>
					<input name="set-src" id="set-src" class="helper-tool-set-src" value="'.$image['url'].'" type="text">
					<input name="img_id" id="img_id" value="'.$image['id'].'" type="hidden"/>
				</p>
				<p class="">
					<label for="data-attr">FocusPoint data- attributes:</label>
					<input class="helper-tool-data-attr" id="data-attr" value="'.htmlspecialchars($image['data']).'" name="data-attr" type="text">	
				</p>
				<p>
				<input name="save" type="submit" class="button button-primary button-large" id="saveImage" value="'.__('Save image', 'wpfancy').'">
				</form>
				</p>
			</div><!-- End Helper Tool -->
		</div>
		<a class="deleteImage" data-id="'.$image['id'].'" data-galid="'.$id.'" href="#">'.__('Delete image', 'wpfancy').'</a>
	';
}

function my_delete_image_callback(){
	$postdata = $_POST;
	$id = $postdata['gal'];
	$img_id = $postdata['id'];
	
	global $wpdb;
	$gallery = $wpdb->get_row( 'SELECT * FROM '.$wpdb->prefix.'galleries where id = '.$id );
	$image = json_decode($gallery->images,true);
	unset($image[$img_id]);
	
	$wpdb->update(
		$wpdb->prefix.'galleries', 
		array('images' => json_encode($image)),
		array('id' => $id)
	);
	
	echo json_encode(array('success' => true));
	wp_die();
}

function my_delete_gal_callback(){
	$postdata = $_POST;
	
	global $wpdb;
	$wpdb->delete($wpdb->prefix.'galleries' , array('id' => $postdata['id']));
	
	$wpdb->delete($wpdb->prefix.'postmeta' , array('meta_value' => $postdata['id'], 'meta_key' => '_my_gallery_value_key'));
	
	echo json_encode(array('success' => true));
	wp_die();
}

function addImage() {
	global $wpdb;
	$galId = $_GET['gal'];
	
	// If Image was added save it and go back to gallery overview
	if(isset($_POST['data-attr']) && $_POST['data-attr'] != ''){
		$gallery = $wpdb->get_row( 'SELECT * FROM '.$wpdb->prefix.'galleries where id = '.$galId );
		$images = json_decode($gallery->images, true);
		$images[$_POST['img_id']] = array(
			'id' => $_POST['img_id'],
			'url' => $_POST['set-src'],
			'data' => stripslashes($_POST['data-attr'])
		);
		
		$wpdb->update(
			$wpdb->prefix.'galleries', 
			array('images' => json_encode($images)),
			array('id' => $galId)
		);
		
		$newURL = 'http://'.$_SERVER['HTTP_HOST'].'/wp-admin/admin.php?page=gallerypage&action=edit&id='.$galId;
		echo '<script type="text/javascript">window.location = "'.$newURL.'"</script>';
		die();
	} else {
	echo '
		<div class="wrap">
	   		<h2>Create Image</h2>
			<input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="'.__('Upload image', 'wpfancy').'">
			
			<div class="spacer"></div>
			
			<div id="Info" class="helperTool">
				<div class="helper-tool">
					<h1>'.__('Click the image to set the FocusPoint.', 'wpfancy').'</h1>
					<!-- <img class="focus-target-reticle" src="../img/focuspoint-target.png" /> -->
					<div class="helper-tool-target">
						<img class="helper-tool-img">
						<img class="reticle" src="'.get_template_directory_uri().'/includes/gallery/img/focuspoint-target.png">
						<img class="target-overlay">
					</div>
					<form method="post">
					<p class="hidden">
						<label for="set-src">Paste in a URL to change the image</label>
						<input name="set-src" id="set-src" class="helper-tool-set-src" type="text">
						<input name="img_id" id="img_id" type="hidden"/>
					</p>
					<p class="hidden">
						<label for="data-attr">FocusPoint data- attributes:</label>
						<input class="helper-tool-data-attr" id="data-attr" name="data-attr" type="text" placeholder="data-focus-x="0" data-focus-y="0" ">	
					</p>
					<p>
					<input name="save" type="submit" class="button button-primary button-large" id="saveImage" value="'.__('Save image', 'wpfancy').'">
					</form>
					</p>
				</div><!-- End Helper Tool -->
			</div>
			
		</div>
	';
	}
}
