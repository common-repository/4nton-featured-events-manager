<?php

if ( ! defined( 'ABSPATH' ) ){ exit; }

class Anton_Feature_Events_Manager_Widget extends WP_Widget {
	protected $defaults;
	protected $sanitize;
	public function __construct() {
		global $anton_fem_widget;
		$this->sanitize = array(
			'plain' => array(
				'title' => __( 'Featured Events', ANTON_FEM ),
				'text' => '',
				'color' => '',
				'order' => '',
				'default_width' => '',
				'large_box_width' => '',
				'small_box_width' => '',
				'default_height' => '',
				'large_box_height' => '',
				'small_box_height' => '',
				'layout' => '',
				'orderby' => '',
				'heading_color' => '',
				'overlay_bc' => '',
				'description' => __( 'Morbi in sem quis dui placerat ornare. Pellentesque odio nisi, euismod in, pharetra a, ultricies in, diam. Sed arcu. Cras consequat.', ANTON_FEM ),
				'default_bg' => ANTON_FEM_IMG_URL . '/no-image.png',
				'background_color' => '',
				'separator_color' => '',
				'read_more_text' => __( 'Read More', ANTON_FEM ),
				'view_more_url' => '',
				'view_more_text' => __( 'View More', ANTON_FEM ),
				'view_more_border_color' => '',
				'view_more_hover_border_color' => '',
				'view_more_text_color' => '',
				'view_more_hover_text_color' => '',
				'view_more_background_color' => '',
				'view_more_hover_background_color' => '',
				'view_more_font_size' => '',
				'view_more_border_width' => '',
				'view_more_padding_left_right' => '',
				'view_more_height' => '',
				'event_title_text_color' => '',
				'event_title_font_size' => '',
				'padding_top' => '',
				'padding_bottom' => '',
				'box_space' => '',
				//'111' => '',
				//'111' => '',				
			),
			'int' => array(
				'category' => 0,
				'thumbnail' => 0,
				'posts_num' => 5,
				'column' => 2,
				'content_limit' => 300,
				'background_image' => 0,
			),
			'bool' => array(
				'enable_background_image' => false,
			),
			'array' => array(
				
			),
		);
		$this->defaults = array_merge(
			$this->sanitize['plain'],
			$this->sanitize['int'],
			$this->sanitize['bool'],
			$this->sanitize['array']
		);
		//$anton_fem_widget = $this->defaults;
		$widget_ops = array(
			'classname' => anton_fem( 'slider ' ),
			'description' => __( ANTON_FEM_NAME . ' display a list of featured events on Events Manager.', ANTON_FEM ),
			'customize_selective_refresh' => true,
		);
		$control_ops = array( 'width' => 500, 'height' => 350 );
		parent::__construct( anton_fem( 'event' ), __( ANTON_FEM_NAME, ANTON_FEM ), $widget_ops, $control_ops );
	}
	public function enqueue_admin_scripts() {
		wp_enqueue_editor();
		wp_enqueue_script( 'text-widgets' );
	}
	public function widget( $args, $instance ) {
		if( ! is_admin() ){
			$instance = wp_parse_args( (array) $instance, $this->defaults );
			global $anton_fem_widget;
			$anton_fem_widget = $instance;
			$anton_fem_widget['randum'] = wp_rand();
			$args = array(
				'post_type'	=> 'event',
				'showposts'	=> $instance['posts_num'],
				'orderby'	=> $instance['orderby'],
				'order' 	=> $instance['order'],
				'meta_key' 	=> 'fem_featured',
				'meta_value'	=> 1,
				'post_status'	=> 'publish',
			);
			if( $instance['category'] ) :
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
				$instance['layout'], 
				apply_filters( 'anton_fem_template_layout', ANTON_FEM_TEMPLATE_PATH, $instance['layout'] )
			);
			anton_fem_get_template_part( 
				'styles', 
				ANTON_FEM_FUNCTIONS_PATH
			);
		}
	}
	public function update( $new_instance, $old_instance ) {
		if( ! empty( $this->sanitize['plain'] ) ) :
			foreach( $this->sanitize['plain'] as $id => $field ) :
				if( ! in_array( $id, array( 'default_bg', 'default_thumbnail' ) ) ) :
					$old_instance[$id] = $new_instance[$id];
				endif;
			endforeach;
		endif;
		if( ! empty( $this->sanitize['int'] ) ) :
			foreach( $this->sanitize['int'] as $id => $field ) :
				$old_instance[$id] = (int) $new_instance[$id];
			endforeach;
		endif;
		if( ! empty( $this->sanitize['bool'] ) ) :
			foreach( $this->sanitize['bool'] as $id => $field ) :
				$old_instance[$id] = (bool) $new_instance[$id];
			endforeach;
		endif;
		if( ! empty( $this->sanitize['array'] ) ) :
			foreach( $this->sanitize['array'] as $id => $field ) :
				$old_instance[$id] = $new_instance[$id];
			endforeach;
		endif;
		return $old_instance;
	}
	public function form( $instance ) {
		$allowed = array();
		$defaults = array();
		foreach( $this->defaults as $id => $field ) :
			$defaults[] = $id;
			$allowed[$id] = array(
		 		'id' => $this->get_field_id( $id ),
		 		'name' => $this->get_field_name( $id )
	 		);
		endforeach;
		$instance['widget_id'] = $this->number;
		anton_fem_edit_fields( 
			wp_parse_args( (array) $instance, $this->defaults ), 
			$allowed, 
			$defaults, 
			ANTON_FEM,
			array(
				'taxonomy' => 'event-categories',
				'layout' => apply_filters( 'anton_fem_layouts', array() ),
			)
		);
	}
}