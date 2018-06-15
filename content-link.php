<?php
/**
 * The template for displaying posts in the Link post format
 *
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="link-bg">
	<header class="entry-header">
		<h1 class="entry-title linktitle">
			<i class="fa fa-link"></i>
		</h1>
	</header><!-- .entry-header -->

	<div class="entry-content">
		
		<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'wpfancy' ),
				the_title( '<span class="screen-reader-text">', '</span>', false )
			) );
		?>
	</div><!-- .entry-content -->

	<?php if ( is_single() ) : ?>
	<?php endif; // is_single() ?>
	</div>
</article><!-- #post -->
<hr/>