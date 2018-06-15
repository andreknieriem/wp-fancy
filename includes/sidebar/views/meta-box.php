<div class="sidebarMetabox">

<p>
	<label for="simple-page-sidebars-page-sidebar-name"><?php echo __( 'Current sidebar:', 'wpfancy' ); ?></label>
	<select name="wpfancy_siderbarname" id="simple-page-sidebars-page-sidebar-name" class="widefat">
		<option value="sidebar-1"><?php echo __( 'Default Sidebar', 'wpfancy' ); ?></option>
		<?php
		foreach ( $custom_sidebars as $sb ) {
			if($sb != 'sidebar-1'){
				printf( '<option value="%s"%s>%s</option>',
					esc_attr( $sb ),
					selected( $sb, $sidebar, false ),
					esc_html( $sb )
				);
			}
		}
		?>
	</select>

	<label for="simple-page-sidebars-page-sidebar-name-new" class="screen-reader-text"><?php __( 'Or create a new sidebar:', 'wpfancy' ); ?></label>
	<input type="text" name="simplepagesidebars_page_sidebar_name_new" id="simple-page-sidebars-page-sidebar-name-new" class="widefat hide-if-js" value="">

</p>
<div class="clear"></div>
</div>