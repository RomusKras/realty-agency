<?php
/*
Plugin Name: AJAXPosts
Plugin URI:
Description:AJAX размещение объявлений
Author: Roman Krasovskiy
Version: 1.0
Author URI:
*/


///----------- EXIT IF ACCESSED DIRECTLY --------------------///

if ( !defined( 'ABSPATH' ) ) exit;

define('PUBSURL', WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ) );
define('PUBPATH', WP_PLUGIN_DIR."/".dirname( plugin_basename( __FILE__ ) ) );
global $post;

///------------- PLUG-IN FILES ------------------------------///
require_once( ABSPATH . 'wp-admin/includes/image.php' );
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/media.php' );


///------------- CONNECTING TO ADMIN-AJAX.PHP -----------------///

function pub_enqueuescripts()
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('pub', PUBSURL.'/js/pub.js', array('jquery'));
    wp_localize_script( 'pub', 'pubajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('wp_enqueue_scripts', 'pub_enqueuescripts');




///----------- PUBLISH A NEW POST AND OUTPUT TO AJAX ------------///
function ObjectPostDownload(){


    $title = wp_strip_all_tags($_POST['titleObject']);
    $desc = wp_strip_all_tags($_POST['descObject']);
    $area = wp_strip_all_tags($_POST['areaObject']);
    $cost = wp_strip_all_tags($_POST['costObject']);
    $live = wp_strip_all_tags($_POST['liveObject']);
    $floor = wp_strip_all_tags($_POST['floorObject']);
    $city = wp_strip_all_tags($_POST['cityObject']);
    $address = wp_strip_all_tags($_POST['addressObject']);
    $type = wp_strip_all_tags($_POST['typeObject']);

    $post_data = array(
        'post_title' => $title,
        'post_content' => $desc,
        'post_status' => 'publish',
        'post_type' => 'realty-object',
        'meta_input' => ['area' => $area, 'cost' => $cost, 'living_area' => $live, 'floor' => $floor, 'address' => $address],
    );

    if(!empty($title)&& !empty($area)&&!empty($cost)&&!empty($floor)&&!empty($city)&&!empty($address)&&!empty($_FILES)) {
    $my_post_id = wp_insert_post($post_data);

    foreach ($_FILES as $file_id => $data) {
        $attachment_id = media_handle_upload($file_id, $my_post_id);
    }
    set_post_thumbnail($my_post_id, $attachment_id);
    wp_set_object_terms($my_post_id, $type, 'property type'); // Ставим тип недвижимости
	//add_post_meta($my_post_id, 'city', $city);
		
		wp_update_post(
            array(
                'ID'          => $my_post_id,
                'post_parent' => $city,
            )
        );
		
	}

        $args = array(
            'post_type' => 'realty-object',
	        'posts_per_page' => 9,

        );

        $obj = new WP_Query($args);

        if ($obj->have_posts()) : while ( $obj->have_posts()) : $obj->the_post();?>

	        <div class="col-xs-4 col-sm-12 col-md-12 col-lg-4">
		        <a href="<?php the_permalink() ?>">
                    <?php echo get_the_post_thumbnail( ); ?>
			        <figure>
				        <h2><?php echo the_title(); ?></h2>
				        <p><i class="fas fa-map-marker-alt"></i><?php esc_attr(the_field('address'));?></p>
				        <p><span>Тип:</span> <?php if(get_field('living_area')== 'true'): echo 'Жилая площадь'; else: echo ('Нежилая площадь'); endif ?></p>
				        <ul>
					        <li><span>Площадь:</span> <?php esc_attr(the_field('area'));?> кв.м.</li>
					        <li><span>Этаж:</span> <?php esc_attr(the_field('floor'));?></li>
				        </ul>
				        <p><span>Стоимость:</span> <?php
                            $numberCost = get_field('cost');
                            echo esc_attr(number_format((float)$numberCost,0,'',' '));?> руб.</p>
			        </figure>
		        </a>
	        </div>

        <?php endwhile; endif; wp_reset_query();
    wp_die();
};


if( defined('DOING_AJAX') ) {
    add_action('wp_ajax_AjaxOb', 'ObjectPostDownload');
    add_action('wp_ajax_nopriv_AjaxOb', 'ObjectPostDownload');
}

// Функция самой формы добавления
function pub_post_form()
{
    ?>
		<section id="forma">
		<div class="container forma">
			<h3>Добавьте свой объект недвижимости</h3>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 offset-lg-1">
					<form  action="" method="" id="my_form" >
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
							<fieldset>
								<input type="text" class="form-control" name="titleObject" id="titleT" placeholder="Наименование объекта"  autocomplete="off" required>
							</fieldset>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
							<fieldset>
								<input type="text" class="form-control" name="addressObject" id="titleA" placeholder="Адрес" autocomplete="off" required>
							</fieldset>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<fieldset>
								<textarea class="form-control" name="descObject" id="descript" rows="3" placeholder="Описание" autocomplete="off" required></textarea>
							</fieldset>
						</div>
						<div class="form-groupp">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
								<fieldset>
									<input type="text" name="areaObject" class="form-control number" id="areaQ" placeholder="Площадь м. кв." autocomplete="off" required>
								</fieldset>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
								<fieldset>
									<input type="text" name="costObject" class="form-control number" id="costQ" placeholder="Стоимость руб."   required>
								</fieldset>
							</div>
							<div class="col-xs-4 col-sm-10 col-sm-12 col-md-12 col-lg-3">
								<div class="checkbox">
									<label>Жилая площадь?</label>
									<input type="checkbox" name="liveObject" id="liveQ" autocomplete="off">
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
								<fieldset>
									<input type="text" class="form-control number" name="floorObject" id="floorQ" placeholder="Этаж"  autocomplete="off" required>
								</fieldset>
							</div>
						</div>
						<div class="form-groupp">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
								<fieldset>
									<select id="cityQ">
										<option disabled selected>Город</option>
										<?php
											$objects = get_posts(array( 'post_type'=>'realty-cities', 'posts_per_page'=>-1, 'orderby'=>'title', 'order'=>'ASC' ));
											if( $objects ){
												foreach( $objects as $object ){
													$tempCityName = $object->post_title;
													echo '<option value="'.$object->ID.'">'.$tempCityName.'</option><br>';
												}
											}
										?>
									</select>
								</fieldset>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
								<fieldset>
									<select id="typeQ">
										<option disabled selected>Тип Недвижимости</option>
										<?php
											$terms = get_terms( [
												'taxonomy' => 'property type',
												'hide_empty' => false,
											] );
											if( $terms && ! is_wp_error($terms) ){
												foreach( $terms as $term ){
													$tempTaxName = $term->name;
													echo '<option value="'.$tempTaxName.'">'.$tempTaxName.'</option>';
												}
											}
											?>
									</select>
								</fieldset>
						</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-5">
							<fieldset>
								<input type="file" multiple="multiple" class="form-control filer" name="my_image_upload" id="imageQ" placeholder=""
								       aria-describedby="fileHelpId" autocomplete="off" required>
							</fieldset>
						</div>
						<div class="response"></div>
						<p class="btn btn-primary col-12" id="custom_button">Добавить</p>
						<div class="ajax-reply"></div>
					</form>
				</div>
			</div>
		</div>
	</section>
    <?php
}
add_shortcode('publish-form', 'pub_post_form');