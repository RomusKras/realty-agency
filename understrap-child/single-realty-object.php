<?php
/**
 * The template for displaying all single posts.
 *
 * @package understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
$container = get_theme_mod( 'understrap_container_type' );
?>

<div class="wrapper" id="single-wrapper">

    <!--===============SINGLE OBJECT========-->
    <section id="object-single">
        <div class="<?php echo $container ?>">
            <div class="row">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="object-name">
                            <h1>Продаётся <?php echo the_title() ?></h1>
                            <ul>
                                <li><?php the_date('j F Y')?></li>
                                <li><?php
                                    $numberCost = get_field('cost');
                                    echo esc_attr(number_format($numberCost,0,'',' '));?> руб.</li>
                            </ul>
                            <p><i class="fas fa-map-marker-alt"></i><?php esc_attr(the_field('address'))  ?></p>
                        </div>
                        <div class="object-description">
                            <?php echo get_the_post_thumbnail( $post->ID ); 
							// New wrapper for images
							$post_id = get_the_ID(); 
							$attrToImages = array(
									'class'  => 'fancybox',
								);
							?>
							<div class="col-md-12 additional-images">
								<div class="row">
									<div class="col-md-3">
										<?php
											$firstURL = wp_get_attachment_image_src( get_post_meta( $post_id, 'second_featured_image', true),'large',false)[0]; // get Url
											echo '<a href="'.$firstURL.'" class="fancybox">';
												echo wp_get_attachment_image(get_post_meta( $post_id, 'second_featured_image', true),'thumbnail',false,$attrToImages); 
											echo '</a>';
										?>
									</div>
									<div class="col-md-3">
										<?php
											$firstURL = wp_get_attachment_image_src( get_post_meta( $post_id, 'third_featured_image', true),'large',false)[0]; // get Url
											echo '<a href="'.$firstURL.'" class="fancybox">';
												echo wp_get_attachment_image(get_post_meta( $post_id, 'third_featured_image', true),'thumbnail',false,$attrToImages); 
											echo '</a>';
										?>
									</div>
									<div class="col-md-3">
										<?php
											$firstURL = wp_get_attachment_image_src( get_post_meta( $post_id, 'fourth_featured_image', true),'large',false)[0]; // get Url
											echo '<a href="'.$firstURL.'" class="fancybox">';
												echo wp_get_attachment_image(get_post_meta( $post_id, 'fourth_featured_image', true),'thumbnail',false,$attrToImages);
											echo '</a>';
										?>
									</div>
									<div class="col-md-3">
										<?php
											
										?>
									</div>
								</div>
							</div>
                            <div class="object-description-bottom">
                                <ul>
                                    <li><span>Площадь:</span> <?php esc_attr(the_field('area'))  ?> кв.м.</li>
                                    <li><span>Этаж:</span> <?php esc_attr(the_field('floor'))  ?></li>
                                </ul>
                                <h2>Описание</h2>
                                <p><?php $objdesc = get_the_content(); echo esc_attr(strip_tags($objdesc))?></p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; endif; ?>
            </div>
        </div>
    </section>

</div><!-- #single-wrapper -->

<?php get_footer(); ?>
