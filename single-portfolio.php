<?php
/**
 * The Template for displaying single portfolio.
 *
 * @package marine
 * @since marine 1.0
 */


get_header();
$xs_portfolio_page = ts_get_current_language_post_id(ot_get_option('portfolio_page'));
$xs_portfolio_link = get_page_link($xs_portfolio_page);

$xs_prev = mod_get_adjacent_post('prev', array('portfolio'));
$xs_next = mod_get_adjacent_post('next', array('portfolio'));

$xs_portfolio_style = get_post_meta($post->ID, 'portfolio_single_style', true);

if (empty($xs_portfolio_style) || $xs_portfolio_style == 'default') {
    $xs_portfolio_style = ot_get_option('portfolio_single_style');
}

$xs_post_format = get_post_format($post);

if ($xs_portfolio_style == 'extended'):
	
	wp_enqueue_script ( 'jquery-flexslider' );
	
    switch ($xs_post_format):
        case 'gallery':
            $gal_imgs = get_post_meta($post->ID, 'gallery_images', true); ?>
            <section class="full-width portfolio-extended-image">
                <?php if (is_array($gal_imgs)): ?>
                    <div class="flexslider main-flexslider light">
                        <ul class="slides">
                            <?php foreach ($gal_imgs as $img): ?>
                                <li class="align-center dark">
                                    <?php echo $res_img = ts_get_resized_image_by_size($img['image'], 'portfolio-single-style1', $img['title'], 'img-responsive'); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php else: ?>
                    
                <img class="img-responsive" src="<?php echo wp_get_attachment_url( get_post_thumbnail_id( $post->ID ));?>" />
                <?php endif; ?>
            </section>
            <?php
            break;

        case 'video':
			
			wp_enqueue_script ( 'fitvids' );
            ?>
            <section class="full-width format-video portfolio-extended-image">
                <?php echo ts_get_embaded_video(get_post_meta($post->ID, 'video_url', true)); ?>
            </section>
            <?php break;
        default:
            ?>
            <section class="full-width format-video portfolio-extended-image">
				<img class="img-responsive" src="<?php echo wp_get_attachment_url( get_post_thumbnail_id( $post->ID ));?>" />

			</section>
			<?php break;
    endswitch; ?>
<?php endif; ?>

<?php
$titlebar_background = get_post_meta($post -> ID, 'titlebar_background', true);
$titlebar_background_style = '';
$class = '';
if (!empty($titlebar_background)):
	$titlebar_background_style = 'style="background-image: url('.$titlebar_background.')"';

	$title_background_position = get_post_meta(get_the_ID(), 'title_background_position', true);
    if (empty($title_background_position) || $title_background_position == 'default'):
        $title_background_position = ot_get_option('default_title_background_position');
    endif;
    if (!empty($title_background_position) && $title_background_position != 'default'):
        $class .= ' ' . $title_background_position;
    endif;

    $title_background_size = get_post_meta(get_the_ID(), 'title_background_size', true);
	
	if (empty($title_background_size) || $title_background_size == 'global'):
        $title_background_size = ot_get_option('default_title_background_size');
	endif;
	
	if (!empty($title_background_size) && $title_background_size != 'default'):
        $class .= ' ' . $title_background_size;
    endif;
endif;
?>

<!-- Page Heading -->
<section class="full-width page-heading style2 dark-blue-bg portfolio-heading <?php echo $class; ?>  <?php if($xs_portfolio_style == 'extended') echo 'portfolio-extended-heading'; ?>" <?php echo $titlebar_background_style; ?>>
	<div class="container">
		<div class="row">
			<div class="col-lg-2 col-md-2 col-sm-2">
				<a href="<?php echo $xs_portfolio_link; ?>" class="portfolio-button"></a>
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8">
				<h1><span class="extra-bold huge"><?php the_title(); ?></span></h1>
			</div>
			<div class="col-lg-2 col-md-2 col-sm-2 portfolio-arrows">
				<?php if (!empty($xs_prev) && $xs_prev->ID != ''): ?>
					<a href="<?php echo get_permalink($xs_prev->ID); ?>" class="portfolio-prev"></a>
				<?php endif; ?>
				<?php if (!empty($xs_next) && $xs_next->ID != ''): ?>
					<a class="portfolio-next" href="<?php echo get_permalink($xs_next->ID); ?>"></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
<!-- /Page Heading -->

<?php

switch ($xs_portfolio_style) {

    case 'extended':
        get_template_part('inc/portfolio-single/single', 'extended');
        break;
    case 'basic':

    default:
        get_template_part('inc/portfolio-single/single', 'basic');
        break;
}
get_footer(); ?>