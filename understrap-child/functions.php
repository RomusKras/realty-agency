<?php
nocache_headers();
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'understrap-styles' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

// END ENQUEUE PARENT ACTION

if(!function_exists('child_configurator_css')):
    function child_configurator_css() {
        wp_enqueue_style('parent-style-min',trailingslashit(get_stylesheet_directory_uri()).'css/all.min.css');
        wp_enqueue_style('parent-style',trailingslashit(get_stylesheet_directory_uri()).'style.css');
    }
    endif;
add_action( 'wp_enqueue_scripts', 'child_configurator_css',15);
// END ENQUEUE PARENT ACTION

/** Remove autoformatting */
remove_filter( 'the_content', 'wpautop' );


/** Init TGM plagins installer */
require get_stylesheet_directory() . '/inc/tgm-list.php';


/*********Custom Post Types*********/


function create_post_type(){
    register_post_type('realty-object',array(
            'labels' => array(
                'name' => 'Недвижимость',
				'singular_name' => 'Объект недвижимости',
				'add_new' => 'Добавить недвижимость',
				'add_new_item' => 'Добавить недвижимость',
				'edit_item' => 'Редактировать недвижимость',
				'new_item' => 'Новая недвижимость',
				'all_items' => 'Вся недвижимость',
				'search_items' => 'Искать недвижимость',
				'not_found' =>  'Недвижимости по заданным критериям не найдено.',
				'not_found_in_trash' => 'В корзине нет недвижимости.',
				'menu_name' => 'Недвижимость',
				// лейблы загрузчика медиафайлов
				'insert_into_item'         => 'Вставить в объект недвижимости',
				'uploaded_to_this_item'    => 'Загружено для этого объекта недвижимости',
				'featured_image'           => 'Изображение недвижимости',
				'set_featured_image'       => 'Установить изображение недвижимости',
				'remove_featured_image'    => 'Удалить изображение недвижимости',
				'use_featured_image'       => 'Использовать как изображение недвижимости',
				// Gutenberg, WordPress 5.0+
				'item_updated'             => 'Объект недвижимости обновлён.',
				'item_published'           => 'Объект недвижимости добавлен.',
				'item_published_privately' => 'Объект недвижимости добавлен приватно.',
				'item_reverted_to_draft'   => 'Объект недвижимости сохранён как черновик.',
				'item_scheduled'           => 'Публикация объекта недвижимости запланирована.',
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite'=> array( 'slug' => 'realty'),
            'menu_position' => 6,
            'menu_icon' => 'dashicons-admin-home',
            'taxonomies' => array( 'property type' ),
            'supports' => array(
                'title',
                'editor',
                'thumbnail',
            ),
        )
    );
    register_post_type('realty-cities',array(
            'labels' => array(
                'name' => 'Город',
                'singular name' => 'Город',
				'add_new' => 'Добавить город',
				'add_new_item' => 'Добавить город',
				'edit_item' => 'Редактировать город',
				'new_item' => 'Новый город',
				'all_items' => 'Все города',
				'search_items' => 'Искать города',
				'not_found' =>  'Городов по заданным критериям не найдено.',
				'not_found_in_trash' => 'В корзине нет городов.',
				'menu_name' => 'Города',

            ),
            'public' => true,
            'has_archive' => true,
            'rewrite'=> array( 'slug' => 'cities' ),
            'menu_position' => 5,
            'menu_icon' => 'dashicons-admin-multisite',
            'supports' => array(
                'title',
                'editor',
                'thumbnail',
            ),
        )
    );
}
add_action( 'init', 'create_post_type' );

/*********Custom Taxonomy*********/

function create_taxonomy(){
    register_taxonomy(
        'property type',
        'realty-object',
        array(
            'label' => __('Типы объектов недвижимости'),
            'rewrite' => array(
                'slug' => 'property-type'
            ),
            'hierarchical' => true,
        )
    );
}
add_action( 'init', 'create_taxonomy' );

add_filter( 'post_updated_messages', 'estate_post_type_messages' );
 
function estate_post_type_messages( $messages ) {
 
	global $post, $post_ID;
 
	$messages[ 'realty-object' ] = array( // название созданного нами типа записей
		0 => '', // Данный индекс не используется.
		1 => 'Объект недвижимости обновлён.',
		2 => 'Поле изменено.',
		3 => 'Поле удалено.',
		4 => 'Объект недвижимости обновлён.',
		5 => isset( $_GET[ 'revision' ] ) ? sprintf( 'Объект недвижимости восстановлен из редакции: %s', wp_post_revision_title( (int) $_GET[ 'revision' ], false ) ) : false,
		6 => 'Объект недвижимости добавлен.',
		7 => 'Объект недвижимости сохранён.',
		8 => 'Отправлено на проверку.',
		9 => sprintf( 'Объект недвижимости запланирован на публикацию на <strong>%1$s</strong>.', date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => 'Черновик объекта недвижимости сохранён',
	);
 
	return $messages;
}

add_action( 'admin_head', 'estate_post_type_help_tab', 25 );
 
function estate_post_type_help_tab() {
 
	$screen = get_current_screen();
 
	// Прекращаем выполнение функции, если находимся на страницах других типов постов
	if ( 'realty-object' !== $screen->post_type ) {
		return;
	}
 
	// Добавляем первую вкладку
	$screen->add_help_tab( array(
		'id'      => 'tab_1',
		'title'   => 'Общая информация',
		'content' => '<h3>Общая информация</h3><p>На этой странице вы сможете найти все загруженные объекты недвижимости.</p>'
	) );
 
	// Добавляем вторую вкладку
	$screen->add_help_tab( array(
		'id'      => 'tab_2',
		'title'   => 'Вторая вкладка',
		'content' => '<h3>Вторая вкладка</h3><p>Содержимое второй вкладки</p>'
	) );
 
}

// Добавим метабокс выбора команды к игроку
add_action('add_meta_boxes', function () {
	add_meta_box( 'city', 'Город недвижимости', 'object_to_city_metabox', 'realty-object', 'side', 'low'  );
}, 1);

// метабокс с селектом городов
function object_to_city_metabox( $post ){
	$teams = get_posts(array( 'post_type'=>'realty-cities', 'posts_per_page'=>-1, 'orderby'=>'post_title', 'order'=>'ASC' ));

	if( $teams ){
		// Scroll
		echo '
		<div style="max-height:200px; overflow-y:auto;">
			<ul>
		';

		foreach( $teams as $team ){
			echo '
			<li><label>
				<input type="radio" name="post_parent" value="'. $team->ID .'" '. checked($team->ID, $post->post_parent, 0) .'> '. esc_html($team->post_title) .'
			</label></li>
			';
		}

		echo '
			</ul>
		</div>';
	}
	else
		echo 'Городов не установлено';
}

// проверка подключения игрока
add_action('add_meta_boxes', function(){
	add_meta_box( 'objects', 'Недвижимость города', 'realty_objects_in_city_metabox', 'realty-cities', 'side', 'low'  );
}, 1);


function realty_objects_in_city_metabox( $post ){
	$realty_objects = get_posts(array( 'post_type'=>'realty-object', 'post_parent'=>$post->ID, 'posts_per_page'=>-1, 'orderby'=>'post_title', 'order'=>'ASC' ));

	if( $realty_objects ){
		foreach( $realty_objects as $rObject ){
			echo $rObject->post_title .'<br>';
		}
	}
	else
		echo 'Недвижимости не установлено';
}


// Дополнительные изображения к объектам недвижимости
$ourDomain = "romusdemo.gq";
//init the meta box
add_action( 'after_setup_theme', 'custom_postimage_setup' );
function custom_postimage_setup(){
    add_action( 'add_meta_boxes', 'custom_postimage_meta_box' );
    add_action( 'save_post', 'custom_postimage_meta_box_save' );
}

function custom_postimage_meta_box(){

    //on which post types should the box appear?
    $post_types = array('realty-object');
    foreach($post_types as $pt){
        add_meta_box('custom_postimage_meta_box',__( 'Дополнительные фото', $ourDomain),'custom_postimage_meta_box_func',$pt,'side','low');
    }
}

function custom_postimage_meta_box_func($post){

    //an array with all the images (ba meta key). The same array has to be in custom_postimage_meta_box_save($post_id) as well.
    $meta_keys = array('second_featured_image','third_featured_image','fourth_featured_image');

    foreach($meta_keys as $meta_key){
        $image_meta_val=get_post_meta( $post->ID, $meta_key, true);
        ?>
        <div class="custom_postimage_wrapper" id="<?php echo $meta_key; ?>_wrapper" style="margin-bottom:20px;">
            <img src="<?php echo ($image_meta_val!=''?wp_get_attachment_image_src( $image_meta_val)[0]:''); ?>" style="width:100%;margin-bottom:5px;display: <?php echo ($image_meta_val!=''?'block':'none'); ?>" alt="">
            <a class="addimage button" style="margin-bottom:5px;" onclick="custom_postimage_add_image('<?php echo $meta_key; ?>');"><?php _e('Добавить',$ourDomain); ?></a><br>
            <a class="removeimage" style="color:#a00;margin-bottom:5px;cursor:pointer;display: <?php echo ($image_meta_val!=''?'block':'none'); ?>" onclick="custom_postimage_remove_image('<?php echo $meta_key; ?>');"><?php _e('Удалить',$ourDomain); ?></a>
            <input type="hidden" name="<?php echo $meta_key; ?>" id="<?php echo $meta_key; ?>" value="<?php echo $image_meta_val; ?>" />
        </div>
    <?php } ?>
    <script>
    function custom_postimage_add_image(key){

        var $wrapper = jQuery('#'+key+'_wrapper');

        custom_postimage_uploader = wp.media.frames.file_frame = wp.media({
            title: '<?php _e('select image',$ourDomain); ?>',
            button: {
                text: '<?php _e('select image',$ourDomain); ?>'
            },
            multiple: false
        });
        custom_postimage_uploader.on('select', function() {

            var attachment = custom_postimage_uploader.state().get('selection').first().toJSON();
            var img_url = attachment['url'];
            var img_id = attachment['id'];
            $wrapper.find('input#'+key).val(img_id);
            $wrapper.find('img').attr('src',img_url);
            $wrapper.find('img').show();
            $wrapper.find('a.removeimage').show();
        });
        custom_postimage_uploader.on('open', function(){
            var selection = custom_postimage_uploader.state().get('selection');
            var selected = $wrapper.find('input#'+key).val();
            if(selected){
                selection.add(wp.media.attachment(selected));
            }
        });
        custom_postimage_uploader.open();
        return false;
    }

    function custom_postimage_remove_image(key){
        var $wrapper = jQuery('#'+key+'_wrapper');
        $wrapper.find('input#'+key).val('');
        $wrapper.find('img').hide();
        $wrapper.find('a.removeimage').hide();
        return false;
    }
    </script>
    <?php
    wp_nonce_field( 'custom_postimage_meta_box', 'custom_postimage_meta_box_nonce' );
}

function custom_postimage_meta_box_save($post_id){

    if ( ! current_user_can( 'edit_posts', $post_id ) ){ return 'not permitted'; }

    if (isset( $_POST['custom_postimage_meta_box_nonce'] ) && wp_verify_nonce($_POST['custom_postimage_meta_box_nonce'],'custom_postimage_meta_box' )){

        //same array as in custom_postimage_meta_box_func($post)
        $meta_keys = array('second_featured_image','third_featured_image','fourth_featured_image');
        foreach($meta_keys as $meta_key){
            if(isset($_POST[$meta_key]) && intval($_POST[$meta_key])!=''){
                update_post_meta( $post_id, $meta_key, intval($_POST[$meta_key]));
            }else{
                update_post_meta( $post_id, $meta_key, '');
            }
        }
    }
}