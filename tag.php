<?php

get_header(); 

get_template_part( 'includes/gallery' ); ?>

<!-- Main Content -->
<div id="mainContent" class="cardWrapper">
<div class="card">
    <div class="cardFace front"><div class="headerbar"></div>
    <div class="content" id="innerContent">
    	<h3><?php printf( __( 'Tag: %s', 'wpfancy' ), '<span>' . single_tag_title( '', false ) . '</span>' ); ?></h3>
    	<?php if ( have_posts() ) : ?>

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
