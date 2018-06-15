<?php

function wpfancy_settings(){
	
	$socialmedias = array(
		'facebook' => 'Facebook',
		'twitter' => 'Twitter',
		'linkedin' => 'Linkedin',
		'xing' => 'Xing',
		'google-plus' => 'Google Plus',
		'github' => 'Github',
		'instagram' => 'Instagram',
		'dribbble' => 'Dribbble',
		'pinterest' => 'Pinterest',
		'reddit' => 'Reddit',
		'soundcloud' => 'Soundcloud',
		'tumblr' => 'Tumblr',
		'flickr' => 'Flickr',
		'foursquare' => 'Foursquare',
		'twitch' => 'Twitch',
		'youtube' => 'Youtube'
	);
	
	ksort($socialmedias);
	
	if(isset($_POST['update'])) {
		$validSettings = array('social','search_items','default_sidebar','enableSearch','logo','logo_id','favicon','default_gal','galleryImage','favicon_id','gacode','maincolor','mainheadcolor','sidebarcolor','sidebarheadcolor','sbtextcolor','sbheadlinecolor','textcolor','headlinecolor','linkcolor','buttoncolor','custom_css');
		$save = array();
		foreach($_POST as $key=>$input){
			if(in_array($key,$validSettings)){
				if($key =='social'){
					foreach($input as $k=>$s){
						if(empty($s)){
							unset($input[$k]);
						}
					}
				}
				$save[$key] = $input;
			}
		}
		update_option( 'wp_fancy_settings', json_encode($save));
		$settings = $save;
	?>
		<div id="message" class="updated fancyupdate"><p><?php echo __('Settings updated', 'wpfancy'); ?></p></div>
	<?php } 
	else {
		$settings = json_decode(get_option('wp_fancy_settings'), true);
	}
	global $wpdb;
	$galleryData = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'galleries Order by id' );
	$sidebars =  json_decode(get_option('wp_fancy_sidebars'), true);
	?>
	<div id="wpfancy_settings">
		<form method="post" action="">
			<input type="hidden" name="update" value="true"/>
			<div class="row">
				<div class="leftCol">
					<div class="logo">
						<img class="logoimg" src="http://fancy.andreknieriem.de/wp-content/themes/wpfancy/resources/img/logo.png"> Settings
					</div>
				</div>
				<div class="rightCol">
					<input name="save" type="submit" class="pull-right button button-primary button-large" value="<?php echo __('Save Settings', 'wpfancy'); ?>">
				</div>
				<div class="clear"></div>
			</div>
			
			<!-- General Settings -->
			<div class="labelRow">
				<strong><?php echo __('General Settings', 'wpfancy'); ?></strong>
			</div>
			<div class="row">
				<div class="half">
					<div class="formline small">
						<label><?php echo __('Logo', 'wpfancy'); ?></label>
						<input type="text" class="uploadUrl" name="logo" value="<?php echo $settings['logo']; ?>"/>
					</div>
					
					<button class="button-primary uploadbtn"><?php echo __('Upload', 'wpfancy'); ?></button>
				</div>
				<div class="half">
					<div class="infoText">
						<?php echo __('Upload a logo image, or enter the URL of an image if its already uploaded. The themes default logo gets applied if the input field is left blank. Max. Dimensions: 190x50', 'wpfancy'); ?>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="row">
				<div class="half">
					<div class="formline small">
						<label><?php echo __('Favicon', 'wpfancy'); ?></label>
						<input type="text" class="uploadUrl" value="<?php echo $settings['favicon']; ?>" name="favicon" />
					</div>
					<button class="button-primary uploadbtn"><?php echo __('Upload', 'wpfancy'); ?></button>
				</div>
				<div class="half">
					<div class="infoText">
						<?php echo __('Specify a favicon for your site. Accepted formats: .ico, .png, .gif ', 'wpfancy'); ?>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="row">
				<div class="half">
					<div class="formline small">
						<label><?php echo __('Default Gallery Image', 'wpfancy'); ?></label>
						<input type="text" class="uploadUrl" name="galleryImage" value="<?php echo $settings['galleryImage']; ?>"/>
					</div>
					
					<button class="button-primary uploadbtn"><?php echo __('Upload', 'wpfancy'); ?></button>
				</div>
				<div class="half">
					<div class="infoText">
						<?php echo __('Upload a default gallery image, or enter the URL of an image if its already uploaded. The themes default image gets applied if the input field is left blank.', 'wpfancy'); ?>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="row">
				<div class="half">
					<div class="formline">
						<label><?php echo __('Default Gallery', 'wpfancy'); ?></label>
						<select name="default_gal">
							<option></option>
							<?php 
							foreach($galleryData as $gallery){
								$selected = '';
								if($gallery->id == $settings['default_gal']) {
									$selected = 'selected="selected"';
								}
								echo '<option '.$selected.' value="'.$gallery->id.'">'.$gallery->name.'</option>';
							}
							?>
						</select>
					</div>
				</div>
				<div class="half">
					<div class="infoText">
						<?php echo __('If you have added a gallery you can use it as default.', 'wpfancy'); ?>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="row">
				<div class="half">
					<div class="formline">
						<label><?php echo __('Default Sidebar', 'wpfancy'); ?></label>
						<select name="default_sidebar">
							<option value="sidebar-1"><?php _e( 'Default Sidebar', 'wpfancy' ); ?></option>
							<?php
								foreach ( $sidebars as $sb ) {
									if($sb != 'sidebar-1'){
										printf( '<option value="%s"%s>%s</option>',
											esc_attr( $sb ),
											selected( $sb, $settings['default_sidebar'], false ),
											esc_html( $sb )
										);
									}
								}
							?>
						</select>
					</div>
				</div>
				<div class="half">
					<div class="infoText">
						<?php echo __('If you have added a gallery you can use it as default.', 'wpfancy'); ?>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<!--
			<div class="row">
				<div class="half">
					<div class="formline">
						<label><?php echo __('Google Analytics Tracking Code', 'wpfancy'); ?></label>
						<textarea name="gacode"><?php echo $settings['gacode']; ?></textarea>
					</div>
				</div>
				<div class="half">
					<div class="infoText">
						<?php echo __('Enter your Google analytics tracking Code here. It will automatically be added so google can track your visitors behavior.', 'wpfancy'); ?>
					</div>
				</div>
				<div class="clear"></div>
			</div>-->
			<!-- General Settings -->
			
			<!-- Search Settings -->
			<div class="labelRow">
				<strong><?php echo __('Search Settings', 'wpfancy'); ?></strong>
			</div>
			<div class="row">
				<div class="half">
					<div class="checkbox checkbox-primary">
						<label>
							<?php 
							$checked = '';
							if($settings['enableSearch'] == 'yes'){
								$checked = 'checked="checked"';
							}
								
							?>
							<input type="checkbox" name="enableSearch" value="yes" <?php echo $checked; ?>><span class="ripple"></span><span class="check"></span>
							<?php echo __('Enable Search', 'wpfancy'); ?>
	                    </label>
	                </div>
				</div>
				<div class="half">
					<div class="infoText">
						<?php echo __('If search is enabled it will displayed right after the navigation.', 'wpfancy'); ?>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="row">
				<div class="half">
					<div class="formline">
						<label><?php echo __('Search pages show at most this amount of posts', 'wpfancy'); ?></label>
						<input type="number" name="search_items" value="<?php echo $settings['search_items']; ?>"/>
					</div>
				</div>
				<div class="half">
					<div class="infoText">
						<?php echo __('If filled, the search pages will display this amount of items per page. If not set the default Blogposts value is taken', 'wpfancy'); ?>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			
			<!-- Search Settings -->
			
			<!-- Styling Settings -->
			<div class="labelRow">
				<strong><?php echo __('Styling', 'wpfancy'); ?></strong>
			</div>
			<div class="row">
				<div class="half">
					<label><?php echo __('Main color', 'wpfancy'); ?></label>
					<div class="colorWrap">
						<input type="text" class="inputText" name="maincolor" value="<?php echo $settings['maincolor']; ?>" />
						<div class="colorSelector">
							<div></div>
						</div>
					</div>
				</div>
				<div class="half">
					<label><?php echo __('Main head color', 'wpfancy'); ?></label>
					<div class="colorWrap">
						<input type="text" class="inputText" name="mainheadcolor" value="<?php echo $settings['mainheadcolor']; ?>" />
						<div class="colorSelector">
							<div></div>
						</div>
					</div>
				</div>
				<div class="clear"></div>
				
				<div class="half">
					<label><?php echo __('Sidebar color', 'wpfancy'); ?></label>
					<div class="colorWrap">
						<input type="text" class="inputText" name="sidebarcolor" value="<?php echo $settings['sidebarcolor']; ?>" />
						<div class="colorSelector">
							<div></div>
						</div>
					</div>
				</div>
				<div class="half">
					<label><?php echo __('Sidebar head color', 'wpfancy'); ?></label>
					<div class="colorWrap">
						<input type="text" class="inputText" name="sidebarheadcolor" value="<?php echo $settings['sidebarheadcolor']; ?>" />
						<div class="colorSelector">
							<div></div>
						</div>
					</div>
				</div>
				<div class="clear"></div>
				
				<div class="half">
					<label><?php echo __('Text color', 'wpfancy'); ?></label>
					<div class="colorWrap">
						<input type="text" class="inputText" name="textcolor" value="<?php echo $settings['textcolor']; ?>" />
						<div class="colorSelector">
							<div></div>
						</div>
					</div>
				</div>
				<div class="half">
					<label><?php echo __('Sidebar Text color', 'wpfancy'); ?></label>
					<div class="colorWrap">
						<input type="text" class="inputText" name="sbtextcolor" value="<?php echo $settings['sbtextcolor']; ?>" />
						<div class="colorSelector">
							<div></div>
						</div>
					</div>
				</div>
				<div class="clear"></div>
				
				<div class="half">
					<label><?php echo __('Headline color', 'wpfancy'); ?></label>
					<div class="colorWrap">
						<input type="text" class="inputText" name="headlinecolor" value="<?php echo $settings['headlinecolor']; ?>" />
						<div class="colorSelector">
							<div></div>
						</div>
					</div>
				</div>
				<div class="half">
					<label><?php echo __('Sidebar Headline color', 'wpfancy'); ?></label>
					<div class="colorWrap">
						<input type="text" class="inputText" name="sbheadlinecolor" value="<?php echo $settings['sbheadlinecolor']; ?>" />
						<div class="colorSelector">
							<div></div>
						</div>
					</div>
				</div>
				<div class="clear"></div>
				
				<div class="half">
					<label><?php echo __('Link color', 'wpfancy'); ?></label>
					<div class="colorWrap">
						<input type="text" class="inputText" name="linkcolor" value="<?php echo $settings['linkcolor']; ?>" />
						<div class="colorSelector">
							<div></div>
						</div>
					</div>
				</div>
				<div class="half">
					<label><?php echo __('Button color', 'wpfancy'); ?></label>
					<div class="colorWrap">
						<input type="text" class="inputText" name="buttoncolor" value="<?php echo $settings['buttoncolor']; ?>" />
						<div class="colorSelector">
							<div></div>
						</div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="row">
				<div class="half">
					<div class="formline">
						<label><?php echo __('Custom CSS', 'wpfancy'); ?></label>
						<textarea name="custom_css"><?php echo $settings['custom_css']; ?></textarea>
					</div>
				</div>
				<div class="half">
					<div class="infoText">
						<?php echo __('Enter your additional CSS Code here. It will automatically be added to the end of the page.', 'wpfancy'); ?>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<!-- Styling Settings -->
			
			<!-- Social Settings -->
			<div class="labelRow">
				<strong><?php echo __('Social Settings', 'wpfancy'); ?></strong>
			</div>
			<div class="row">
				<div class="half">
					<div class="formline small">
						<label>Social media profiles</label>
						<select class="socialselect">
							<?php foreach($socialmedias as $key=>$socialmedia){
								echo '<option value="'.$key.'">'.$socialmedia.'</option>';							
							}
							?>
						</select>
					</div>
					<button class="button-primary socialadd"><?php echo __('Add','wpfancy'); ?></button>
				</div>
				<div class="half">
					<div class="infoText">
						<?php echo __('Add your social media profiles here. They will be displayed at the bottom of the sidebar.', 'wpfancy'); ?>
					</div>
				</div>
				<div class="clear"></div>
				<div class="socialmediaprofiles">
				<?php 
				if(!empty($settings['social']) && is_array($settings['social'])){
					foreach($settings['social'] as $key=>$profile){?>
						<div class="profiles">
						<div class="half">
							<div class="formline small <?php echo $key; ?>">
								<label><?php
								$test = $socialmedias[$key];
								echo $test; ?>
								</label>
								<input type="text" value="<?php echo $profile; ?>" name="social[<?php echo $key;?>]"/>
							</div>
							<button class="removesocial"><?php echo __('Remove', 'wpfancy'); ?></button>
						</div>
						</div>
					<?php							
					}
				}
				?>
				</div>
			</div>
			<!-- Social Settings END -->
			<div class="row">
				<div class="leftCol">
				</div>
				<div class="rightCol">
					<input name="save" type="submit" class="pull-right button button-primary button-large" value="<?php echo __('Save Settings', 'wpfancy'); ?>">
				</div>
				<div class="clear"></div>
			</div>
		</form>
	</div>
	
	<?php
}
