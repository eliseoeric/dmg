<?php
/**
 * The Template for displaying all single posts.
 *
 * @package marine
 * @since marine 1.0
 */
get_header();
$class = ts_check_if_any_sidebar(
	'col-lg-12 col-md-12 col-sm-12 small-padding',
	'col-lg-9 col-md-8 col-sm-8 small-padding',
	'');

if (ts_get_single_post_sidebar_position() == 'left') {
	$class .= ' col-md-push-4 col-lg-push-3';
}
?>
    <div class="row">
        <section class="<?php echo $class; ?>">
            <?php if (have_posts()) : ?>
                <?php /* Start the Loop */ ?>
				<?php while (have_posts()) : the_post(); ?>
                    <?php the_content(); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'marine' ), 'after' => '</div>' ) ); ?>
                <?php endwhile; ?>
				<?php if (get_post_meta($post->ID, 'show_comment_form', true) != 'no'):
					if (comments_open() || '0' != get_comments_number() ): ?>
						<div class="row">
							<div class="col-sm-12"><?php comments_template('', true); ?></div>
						</div>
					<?php endif;
				endif;
				?>
            <?php else : //No posts were found ?>
                <?php get_template_part('no-results'); ?>
            <?php endif; ?>
        </section>
        <?php ts_get_single_post_sidebar('left'); ?>
        <?php ts_get_single_post_sidebar('right'); ?>
    </div>
<?php get_footer();