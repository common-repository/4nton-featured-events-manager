<?php

/**
 * Plugin Name: Anton Featured Events Manager
 * Plugin URI: https://www.anthonycarbon.com/
 * Description: <strong>Anton Featured Events Manager</strong> is an addons of <strong>Events Manager</strong> created By <strong>Marcus Sykes</strong>. This plugin allow select an event you wish to be featured on your front-end page sections. Featured an event can be done via edit event page or via events dashboard list page. Enjoy using this plugin.
 * Text Domain: anton-fem
 * Version: 1.0.4
 * Author: <a href="https://www.anthonycarbon.com/">Anthony Carbon</a>
 * Author URI: https://www.anthonycarbon.com/
 * Donate link: https://www.paypal.me/anthonypagaycarbon
 * Tags: featured, events, widget, wdes, manager, layout, anthonycarbon.com
 * Requires at least: 4.4
 * Tested up to: 5.0
 * Stable tag: 1.0.4
 **/

if ( ! defined( 'ABSPATH' ) ){ exit; }

if ( ! class_exists( 'Anton_Feature_Events_Manager' ) ) :

class Anton_Feature_Events_Manager {
	public function __construct() {
		$this->define_constants();
		$this->register();
		$this->includes();
		$this->init_hooks();
	}
	private function define_constants() {
		$this->define( 'ANTON_FEM', 'anton-fem' );
		$this->define( 'ANTON_FEM_NAME', 'Anton Featured Events Manager' );
		$this->define( 'ANTON_FEM_BN', plugin_basename( __FILE__ ) );
		$this->define( 'ANTON_FEM_URL', plugin_dir_url(__FILE__) );
		$this->define( 'ANTON_FEM_IMG_URL', ANTON_FEM_URL . 'assets/images' );
		$this->define( 'ANTON_FEM_JS_URL', ANTON_FEM_URL . 'assets/js' );
		$this->define( 'ANTON_FEM_CSS_URL', ANTON_FEM_URL . 'assets/css' );
		// PATH
		$this->define( 'ANTON_FEM_PATH', plugin_dir_path( __FILE__ ) );
		$this->define( 'ANTON_FEM_LIB_PATH', ANTON_FEM_PATH . 'lib' );
		$this->define( 'ANTON_FEM_ADMIN_PATH', ANTON_FEM_LIB_PATH . '/admin' );
		$this->define( 'ANTON_FEM_CORE_PATH', ANTON_FEM_LIB_PATH . '/core' );
		$this->define( 'ANTON_FEM_FUNCTIONS_PATH', ANTON_FEM_LIB_PATH . '/functions' );
		$this->define( 'ANTON_FEM_TEMPLATE_PATH', ANTON_FEM_LIB_PATH . '/templates' );
		// DIR
		$this->define( 'ANTON_FEM_PARENT_THEME_DIR', get_template_directory() );
		$this->define( 'ANTON_FEM_CHILD_THEME_DIR', get_stylesheet_directory() );
	}
	private function init_hooks() {
		//add_filter( 'plugin_action_links', array( $this, 'settings_url' ), 10, 2 );
		add_action( 'admin_print_styles', array( $this, 'admin_styles' ) );
		add_action( 'admin_print_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'styles_scripts' ) );
		add_action( 'admin_init', array( $this, 'register_setting' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_filter( 'manage_event_posts_columns', array( $this, 'columns' ) );
		add_action( 'manage_event_posts_custom_column', array( $this, 'content' ), 10, 3 );
		add_action( 'wp_ajax_anton_fem_ajax', array( $this, 'ajax' ) );
		add_action( 'wp_ajax_nopriv_anton_fem_ajax', array( $this, 'ajax' ) );
		add_action( 'save_post',  array( $this, 'save_post' ) );
		add_action( 'add_meta_boxes',  array( $this, 'add_meta_boxes' ) );
		add_action( 'widgets_init', array( $this, 'load_widgets' ) );
		add_shortcode( 'anton-fem-widget', array( $this, 'shortcode' ) );
	}
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}
	private function register() {
		
	}
	public function includes() {
		include_once( ANTON_FEM_FUNCTIONS_PATH . '/functions.php' );
		//nclude_once( ANTON_FEM_ADMIN_PATH . '/settings.php' );
		include_once( ANTON_FEM_ADMIN_PATH . '/class-walker-page-checkbox.php' );
		include_once( ANTON_FEM_ADMIN_PATH . '/fields.php' );
		include_once( ANTON_FEM_ADMIN_PATH . '/widget.php' );
	}
	public function settings_url( $links, $file ){
		if ( $file != ANTON_FEM_BN ) { return $links; }
		array_unshift(
			$links,
			sprintf(
				'<a href="%s?page=%s">%s</a>',
				esc_url( admin_url( 'admin.php' ) ),
				ANTON_FEM,
				esc_html__( 'Settings', ANTON_FEM )			
			)
		);
		return $links;
	}
	public function admin_styles(){
		wp_register_style( ANTON_FEM . '-admin', ANTON_FEM_CSS_URL . '/admin.css' );
		wp_enqueue_style( ANTON_FEM . '-admin' );
	}	
	public function admin_scripts() {
		wp_enqueue_style( 'wp-color-picker' ); 
		wp_enqueue_script( 'wp-color-picker' ); 
		wp_enqueue_media(); 
		wp_register_script( ANTON_FEM . '-admin', ANTON_FEM_JS_URL . '/admin.js', array( 'jquery' ) );
		wp_enqueue_script( ANTON_FEM . '-admin' );
		wp_localize_script(
			ANTON_FEM . '-admin',
			'anton_fem',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'spinnir' => ANTON_FEM_IMG_URL . '/loading.gif',
				'slug' => ANTON_FEM
			)
		);
	}
	public function styles_scripts(){
		// styles
		wp_register_style( ANTON_FEM . '-style', ANTON_FEM_CSS_URL . '/style.css' );
		wp_enqueue_style( ANTON_FEM . '-style' );
		// scripts
		wp_register_script( ANTON_FEM . '-bxslider', ANTON_FEM_JS_URL .'/bxslider.min.js', array( 'jquery' ), false );
		wp_register_script( ANTON_FEM . '-script', ANTON_FEM_JS_URL .'/script.js', array( 'jquery' ), false );
		wp_enqueue_script( ANTON_FEM . '-bxslider' );
		wp_enqueue_script( ANTON_FEM . '-script' );
		wp_localize_script(
			ANTON_FEM . '-script',
			'anton_fem',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'slug' => ANTON_FEM,
				'inline_css' => array(
					'content_width' => anton_fem_get_option( 'content_width' ) ? : anton_fem_content_width()
				)
			)
		);
	}
	public function register_setting(){
		register_sidebar( array(
			'id'          	=> ANTON_FEM,
			'name'        	=> __( 'Featured Event Widgets', ANTON_FEM ),
			'description'	=> __( 'Drug ' . ANTON_FEM_NAME . ' widgets here to get your shortcode and configure the settings.', ANTON_WIDGETS ),
		) );
	}
	public function admin_menu(){  
		
	}
	public function columns( $defaults ) {
		$defaults['featured'] = __( 'Featured', ANTON_FEM );
		return $defaults;
	}
	public function content( $column_name, $post_id ) {
		switch( $column_name ) {
			case 'featured' : 
				$iconurl1 = ANTON_FEM_IMG_URL . '/star-not-active-icon.png';
				$iconurl2 = ANTON_FEM_IMG_URL . '/star-active-icon.png';
				$opacity = get_post_meta( $post_id, 'fem_featured', true ) ? ANTON_FEM . '-featured-active' : ANTON_FEM . '-featured-not-active';
				printf(
					'<span id="%s-featured" class="%s-featured %s-featured-%s %s" data-id="%s"><img class="%s-img-1" src="%s" alt="%s" /><img class="%s-img-2" src="%s" alt="%s" /></span>',
					ANTON_FEM,
					ANTON_FEM,
					ANTON_FEM,
					$post_id,
					$opacity,
					$post_id,
					ANTON_FEM,
					$iconurl1,
					get_the_title( $post_id ),
					ANTON_FEM,
					$iconurl2,
					get_the_title( $post_id )
				);
				break;
		}
	}
	public function ajax(){
		$r = $_POST;
		update_post_meta( $r['post_id'], 'fem_featured', $r['featured'] );
		$iconurl1 = ANTON_FEM_IMG_URL . '/star-not-active-icon.png';
		$iconurl2 = ANTON_FEM_IMG_URL . '/star-active-icon.png';
		$opacity = get_post_meta( $r['post_id'], 'fem_featured', true ) ? ANTON_FEM .'-featured-active' : ANTON_FEM .'-featured-not-active';
		printf(
			'<span id="%s-featured" class="%s-featured %s-featured-%s %s" data-id="%s"><img class="%s-img-1" src="%s" alt="%s" /><img class="%s-img-2" src="%s" alt="%s" /></span>',
			ANTON_FEM,
			ANTON_FEM,
			ANTON_FEM,
			$r['post_id'],
			$opacity,
			$r['post_id'],
			ANTON_FEM,
			$iconurl1,
			get_the_title( $r['post_id'] ),
			ANTON_FEM,
			$iconurl2,
			get_the_title( $r['post_id'] )
		);
		die();
	}
	public function add_meta_boxes() {
		if( get_post_type() != 'event' ){ return; }
		add_meta_box(
			'featured_edit_content',
			__( 'Featured Events', ANTON_FEM ),
			array( $this, 'featured_edit_content' ),
			'',
			'side',
			'high'
		);
	}
	public function featured_edit_content(){
		$value = get_post_meta( get_the_ID(), 'fem_featured', true );
		?>
		<p id="featured-wrap" style="padding:0;">
            <label>
                <input type="checkbox" name="fem_featured" id="fem_featured" value="1" <?php checked( 1, $value ); ?> />
                Enable / Disable
            </label>
		</p>
		<?php
	}
	public function save_post( $post_id ) {
		update_post_meta( $post_id, 'fem_featured', $_POST['fem_featured'] );
	}
	public function load_widgets() {
		register_widget( 'Anton_Feature_Events_Manager_Widget' );
	}
	public function shortcode( $atts ) {
		global $anton_fem_widget;
		$r = shortcode_atts( array( 'id' => 0 ), $atts );
		ob_start();
		$instance = get_option( 'widget_anton-fem-event' );
		$anton_fem_widget = $instance[$r['id']];
		$anton_fem_widget['randum'] = wp_rand();
		$args = array(
			'post_type'	=> 'event',
			'showposts'	=> $anton_fem_widget['posts_num'],
			'orderby'	=> $anton_fem_widget['orderby'],
			'order' 	=> $anton_fem_widget['order'],
			'meta_key' 	=> 'fem_featured',
			'meta_value'	=> 1,
			'post_status'	=> 'publish',
		);
		if( $anton_fem_widget['category'] ) :
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'event-categories',	
					'field' => 'term_id',
					'terms' => $r['category'],
				),
			);
		endif;
		$anton_fem_widget['results'] = new WP_Query( $args );
		anton_fem_get_template_part( 
			$anton_fem_widget['layout'], 
			apply_filters( 'anton_fem_template_layout', ANTON_FEM_TEMPLATE_PATH, $anton_fem_widget['layout'] )
		);
		anton_fem_get_template_part( 
			'styles', 
			ANTON_FEM_FUNCTIONS_PATH
		);
		return ob_get_clean();
	}
}

add_action( 'plugins_loaded', 'anton_fem_plugins_loaded', 5 );
function anton_fem_plugins_loaded(){
	new Anton_Feature_Events_Manager;
	$_GLOBAL['anton_fem_widget'] = array();
}

endif;