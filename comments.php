<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 */

if ( post_password_required() )
	return;
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
				printf( _nx( 'One comment on', '%1$s comments on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'wpfancy' ),
					number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
			?>
		</h2>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 50,
				) );
			?>
		</ol><!-- .comment-list -->

		<?php
			// Are there comments to navigate through?
			if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
		?>
		<nav class="navigation comment-navigation" role="navigation">
			<h1 class="screen-reader-text section-heading"><?php _e( 'Comment navigation', 'wpfancy' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'wpfancy' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'wpfancy' ) ); ?></div>
		</nav><!-- .comment-navigation -->
		<?php endif; // Check for comment navigation ?>

		<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="no-comments"><?php _e( 'Comments are closed.' , 'wpfancy' ); ?></p>
		<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php 
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$args = array('fields' =>  array(
	  'author' =>
	    '<p class="comment-form-author"><label for="author">' . __( 'Name', 'wpfancy' ) . ( $req ? '<span class="required">*</span>' : '' ) .'</label> ' .
	    '<input id="author" required="required" name="author" type="text" placeholder="' . __( 'Name', 'wpfancy' ) . '" value="' . esc_attr( $commenter['comment_author'] ) .
	    '" size="30"' . $aria_req . ' /></p>',
	
	  'email' =>
	    '<p class="comment-form-email"><label for="email">' . __( 'Email', 'wpfancy' ) . ( $req ? '<span class="required">*</span>' : '' ) .'</label> ' .
	    '<input id="email" name="email" required="required" type="text" placeholder="' . __( 'Email', 'wpfancy' ) . '" value="' . esc_attr(  $commenter['comment_author_email'] ) .
	    '" size="30"' . $aria_req . ' /></p>',
	
	  'url' =>
	    '<p class="comment-form-url"><label for="url">' . __( 'Website', 'wpfancy' ) . '</label>' .
	    '<input id="url" name="url" type="text" placeholder="' . __( 'Website', 'wpfancy' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) .
	    '" size="30" /></p>',
	));
	
	comment_form($args); 
	?>

</div><!-- #comments -->