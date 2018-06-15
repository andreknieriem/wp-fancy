<?php
/**
 * The default template for displaying content
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-header">
		<div class="entry-meta">
			<?php wpfancy_entry_meta(); ?>
		</div><!-- .entry-meta -->
	</div><!-- .entry-header -->

	<?php if ( !is_single() && !is_page() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary">
		<?php the_excerpt(); 
		
		$link = ''.esc_url(get_permalink( get_the_ID() ));
		echo '<a class="readmore" href="'.$link.'" class="more-link"><i class="fa fa-angle-right"></i> '.__('read more', 'wpfancy').'</a> <hr/>';
		
		?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'twentythirteen' ),
				the_title( '<span class="screen-reader-text">', '</span>', false )
			) );

			wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentythirteen' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
		?>
	</div><!-- .entry-content -->
	<div class="entry-comment">
		<?php if ( comments_open() && ! is_single() ) : ?>
			<div class="comments-link">
				<?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a comment', 'wpfancy' ) . '</span>', __( 'One comment so far', 'wpfancy' ), __( 'View all %s comments', 'wpfancy' ) ); ?>
			</div><!-- .comments-link -->
		<?php endif; // comments_open() ?>
	</div>
	<?php endif; ?>
</article><!-- #post -->
