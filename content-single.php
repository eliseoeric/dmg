<?php
/**
 * The default template for displaying single post content
 *
 * @package marine
 * @since marine 1.0
 */

$classes = array(
	'post',
	( get_post_format() ? 'format-' . get_post_format() : '' ),
	'center'
);
?>
<!-- Post Item -->
<div class="blog-post col-lg-12 col-md-12 col-sm-12 <?php post_class( $classes ); ?>">
	<div class="blog-post-list">

		<div class="blog-post-meta"><?php get_template_part( 'inc/post-date' ); ?></div>

		<!-- Post Content -->
		<div class="blog-post-content">
			<!-- Post Image -->
			<div class="post-thumbnail">
				<?php
				$thumb = '';
				$add_thumb = false;
				switch ( get_post_format() ) {
					case 'gallery':
						$gallery = get_post_meta( $post->ID, 'gallery_images', true );
						if ( is_array( $gallery ) && count( $gallery ) > 0 ) {
							?>
							<?php $thumb = "
									<div class='flexslider one-col control-nav'>
										<ul class='slides'>";
							foreach ( $gallery as $image ) {
								$thumb .= "<li>" . ts_get_resized_image_sidebar( $image['image'], array(
										'full',
										'one-sidebar',
										'two-sidebars'
									), $image['title'] ) . "</li>";
							}
							$thumb .= "
										</ul>
									  </div>
									  <div class='clear'></div>";
						} else {
							$add_thumb = true;
						}
						break;
					case 'video':

						$url = get_post_meta( $post->ID, 'video_url', true );
						if ( ! empty( $url ) ) {
							$embadded_video = ts_get_embaded_video( $url );
						} else if ( empty( $url ) ) {
							$embadded_video = get_post_meta( $post->ID, 'embedded_video', true );
						}

						if ( isset( $embadded_video ) && ! empty( $embadded_video ) ): ?>
							<div class="format-video">

								<?php echo $embadded_video; ?>

							</div>
						<?php else:
							$add_thumb = true;
						endif;
						break;
					case 'audio':
						?>
						<div class="blog-format-audio">
							<?php $audio_url = get_post_meta( get_the_ID(), 'audio_url', true );
							if ( $audio_url != '' ):?>
								<audio>
									<source type="audio/mpeg" src="<?php echo $audio_url; ?>"></source>
									<?php _e( 'Your browser does not support the audio element.', 'marine' );?>
								</audio>
							<?php else:
								$add_thumb = true;
							endif; ?>
						</div>
						<?php break;
					case 'quote': ?>
						<blockquote>
							<p><?php echo get_post_meta( get_the_ID(), 'quote_text', true ); ?></p>
							<span
								class="author"><?php echo get_post_meta( get_the_ID(), 'author_text', true ); ?></span>
						</blockquote>
						<?php break;
					default:
						$add_thumb = true;
				}

				if ( $add_thumb ):
					$thumb = ts_get_resized_post_thumbnail_sidebar( $post->ID, array(
						'full',
						'one-sidebar',
						'two-sidebars'
					), get_the_title() );
					echo $thumb;
				endif;
				?>
			</div>
			<!-- /Post Image -->
			<div class="post-content social-media-wrapper">
				<ul class="post-meta">
					<li><?php echo ts_post_categories( get_the_ID() ); ?></li>
					<li><?php comments_number( __( 'No comments', 'marine' ), __( '1 comment', 'marine' ), __( '% comments', 'marine' ) ); ?></li>
				</ul>
				<?php ts_share_buttons( 'social-media' ); ?>
				<h1><?php get_the_title(); ?></h1>
				<?php the_content(); ?>
				<?php wp_link_pages( array(
					'before' => '<div class="page-link">' . __( 'Pages:', 'marine' ),
					'after'  => '</div>'
				) ); ?>
			</div>
		</div>
	</div>
	<!-- /Post Content -->

</div>




