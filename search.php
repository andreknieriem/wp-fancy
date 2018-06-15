<?php

get_header(); 

get_template_part( 'includes/gallery' ); ?>

<!-- Main Content -->
<div id="mainContent" class="cardWrapper">
<div class="card">
    <div class="cardFace front"><div class="headerbar"></div>
    <div class="content" id="innerContent">
    	
    	<header class="page-header">
			<h2 class="page-title"><?php printf( __( 'Search Results for: %s', 'twentythirteen' ), get_search_query() ); ?></h2>
		</header>
    	
    	<?php 
		$settings = json_decode(get_option('wp_fancy_settings'), true);
		$args = array();
		if(isset($settings['search_items']) && $settings['search_items'] > 0) {
			$args['posts_per_page'] = $settings['search_items'];
			query_posts( $args );
		}
    	
    	if ( have_posts() ) : ?>

			<?php
			// Start the loop.
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'content', get_post_format() );

			// End the loop.
			endwhile;

			// Previous/next page navigation.
			get_pagination();

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'content', 'none' );

		endif;
		?>
    </div>
</div>
</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
