<?php

get_header(); 
?>

<div id="gallery" class="cardWrapper closed">
  <div class="card">
    <div class="cardFace front">
    	<div class="focuspoint galImage active" data-focus-x="0.00" data-focus-y="0.00" data-focus-w="2000" data-focus-h="1031">
			<img src="<?php echo get_template_directory_uri().'/resources/img/404.jpg'; ?>" />
		</div>
    	
		<div class="navigation">
    		<button class="prevButton"><i class="fa fa-chevron-left"></i></button>
    		<button class="nextButton"><i class="fa fa-chevron-right"></i></button>
    		<button class="closeGallery"><i class="fa fa-times"></i> <?php echo __('Close Gallery', 'wpfancy'); ?></button>
    	</div>
    	<button class="openGallery"><i class="fa fa-camera"></i> <?php echo __('Open Gallery', 'wpfancy'); ?></button>
    </div>
  </div>
</div>
<!-- Main Content -->
<div id="mainContent" class="cardWrapper">
<div class="card">
    <div class="cardFace front"><div class="headerbar"></div>
    <div class="content" id="innerContent">
    	<div class="page-content">
    		<h1>Page not found</h1>
			<p>The page you are looking for is not here.<br>
				Why don’t you try the <a href="/">homepage?</a>
			</p>
		</div><!-- .page-content -->
    </div>
</div>
</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
