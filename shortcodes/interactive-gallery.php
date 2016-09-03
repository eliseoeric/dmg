<?php 

function dmg_interactive_gallery( $atts, $content = null )
{
	$a = shortcode_atts( array(
		'class' => '',
		'id' => '',
	), $atts);

	wp_enqueue_script( 'interactive-slide' );


	ob_start();
	?>
	<p class="info info--size">Please view on a larger screen to see the 3D display.</p>
	<p class="info info--support">Unfortunately, your browser does not support 3D interactions.</p>

	<div id="slideshow" class="slideshow">

		<?php echo do_shortcode( $content ); ?>

		<?php get_slide_nav(); ?>
		<?php get_slide_titles(); ?>
	</div>

	<?php
	$html = ob_get_clean();

	return $html;
}
add_shortcode( 'interactive_gallery', 'dmg_interactive_gallery' );

function get_slide_nav() 
{
	ob_start();
	?>
	<nav class="nav">
		<a href="#" class="nav__item"><span class="text-hidden">The Master Bedroom</span></a>
		<a href="#" class="nav__item"><span class="text-hidden">The Dining Room</span></a>
		<a href="#" class="nav__item"><span class="text-hidden">The Bathroom</span></a>
		<a href="#" class="nav__item"><span class="text-hidden">The Office</span></a>
	</nav>
	<?php
	$html = ob_get_clean();

	return $html;
}

function get_slide_titles() 
{
	ob_start();
	?>
	<div class="titles">
		<h2 class="title">The Bedroom <span class="title__sub">Relax</span></h2>
		<h2 class="title">The Living Room <span class="title__sub">Socialize</span></h2>
		<h2 class="title">The Kitchen <span class="title__sub">Create</span></h2>
		<h2 class="title">The Bathroom <span class="title__sub">Refresh</span></h2>
	</div>
	<?php
	$html = ob_get_clean();

	return $html;
}


function get_interactive_scene( $atts, $content = null ) 
{
	$a = shortcode_atts( array(
		'class' => '',
		'id' => '',
	), $atts);

	ob_start();
	?>
	<!-- Start Slide -->
	<div class="slide">
		<div class="scene">
			<div class="views">
				<?php echo do_shortcode( $content ); ?>
			</div>
		</div>
	</div>
	<!-- end slide -->
	<?php
	$html = ob_get_clean();

	return $html;
}
add_shortcode( 'interactive_scene', 'get_interactive_scene' );

function get_interactive_view( $atts, $content = null ) 
{
	$a = shortcode_atts( array(
		'img' => '',
		'title' => '',
		''
	), $atts);

	ob_start();
	?>
	<div class="view">
		<img class="view__img" src="<?= $a['img']; ?>" alt="<?= $a['title']; ?>">
		<?php echo do_shortcode( $content ); ?>
	</div>

	<?php
	$html = ob_get_clean();

	return $html;
}
add_shortcode( 'interactive_view', 'get_interactive_view' );

function get_interacive_item( $atts, $content = null ) 
{
	$a = shortcode_atts( array(
		'img' => '',
		'class' => '',
		'title' => '',
		'price' =>'',
		'url' => '',
		'rotate' => '',
		'transform' => ''
	), $atts);

	ob_start();
	?>
	<div class="item <?= $a['class'] ?>">
		<img class="item__img" src="<?= $a['img'] ?>" alt="<?= $a['title'] ?>" data-transform-z="<?= $a['transform']; ?>" />
		<div class="item__info">
			<h3 class="item__title"><?= $a['title'] ?></h3>
			<div class="item__price"><sup>$</sup><?= $a['price']; ?></div>
			<button class="button button--buy"><i class="icon icon--shopping-cart"></i><span class="text-hidden">Add to cart</span></button>
		</div>
		<button class="button button--close"><i class="icon icon--close"></i><span class="text-hidden">Close</span></button>
	</div>
	<?php
	$html = ob_get_clean();

	return $html;

}
add_shortcode( 'interactive_item', 'get_interacive_item' );