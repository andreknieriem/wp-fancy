<!-- Sidebar -->
<div id="sidebar" class="cardWrapper">
  <div class="card">
    <div class="cardFace front">
    	<div class="headerbar"></div>
    	<div class="content">
    		<?php
			if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
				<div id="tertiary" class="sidebar-container" role="complementary">
					<div class="sidebar-inner">
						<div class="widget-area">
							<?php dynamic_sidebar( 'sidebar-1' ); ?>
						</div><!-- .widget-area -->
					</div><!-- .sidebar-inner -->
				</div><!-- #tertiary -->
			<?php endif; ?>
    		
    		<div class="footerLine">
    			<?php 
    			
    			$settings = json_decode(get_option('wp_fancy_settings'), true);
					echo '<div class="socialIcons">';
    			foreach($settings['social'] as $key=>$profile){
    				echo '<a target="_blank" href="'.$profile.'"><i class="fa fa-'.$key.'"></i></a>';
    			}
					echo '<div class="clear"></div></div>';
    			if ( is_active_sidebar( 'footercontent' ) ) { 
    				dynamic_sidebar( 'footercontent' ); 
					}
    			?>
    		</div>
    	</div>
    </div>
  </div>
</div>