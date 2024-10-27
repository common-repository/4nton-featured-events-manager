<?php

function anton_fem( $text, $separator = '-' ){
	$texts = explode( " ", $text );
	$output = '';
	$space = ' ';
	$count = ( count( $texts ) - 1 );
	foreach( $texts as $key => $text ){
		$output .= ANTON_FEM . $separator . $text;
		if( $key < $count ){
			$output .= $space;
		}
	}
	return $output;
}

function anton_fem_e( $text, $separator = '-' ){
	$texts = explode( " ", $text );
	$output = '';
	$space = ' ';
	$count = ( count( $texts ) - 1 );
	foreach( $texts as $key => $text ){
		$output .= ANTON_FEM . $separator . $text;
		if( $key < $count ){
			$output .= $space;
		}
	}
	echo $output;
}

function anton_fem_get_column_class( $column ){
	switch ( (string) $column ){
		case "2":
			return anton_fem( 'one-half' );
			break;
		case "3":
			return anton_fem( 'one-third' );
			break;
		case "2/3":
			return anton_fem( 'two-thirds' );
			break;
		case "4":
			return anton_fem( 'one-fourth' );
			break;
		case "2/4":
			return anton_fem( 'two-fourths' );
			break;
		case "3/4":
			return anton_fem( 'three-fourths' );
			break;
		case "5":
			return anton_fem( 'one-fifth' );
			break;
		case "2/5":
			return anton_fem( 'two-fifths' );
			break;
		case "3/5":
			return anton_fem( 'three-fifths' );
			break;
		case "4/5":
			return anton_fem( 'four-fifths' );
			break;
		case "6":
			return anton_fem( 'one-sixth' );
			break;
		case "2/6":
			return anton_fem( 'two-sixths' );
			break;
		case "3/6":
			return anton_fem( 'three-sixths' );
			break;
		case "4/6":
			return anton_fem( 'four-sixths' );
			break;
		case "5/6":
			return anton_fem( 'five-sixths' );
			break;
		default :
			return anton_fem( 'full' );
			break;	
	}
}

function anton_fem_get_template_part( $filename, $path = false ){
	if ( file_exists( ANTON_FEM_CHILD_THEME_DIR . "/anton-featured-events-manager/$filename.php" ) ) {
		include( ANTON_FEM_CHILD_THEME_DIR . "/anton-featured-events-manager/$filename.php" );
		return;
	}
	if ( file_exists( ANTON_FEM_PARENT_THEME_DIR . "/anton-featured-events-manager/$filename.php" ) ) {
		include( ANTON_FEM_PARENT_THEME_DIR . "/anton-featured-events-manager/$filename.php" );
		return;
	}
	if( $path && file_exists( $path . "/$filename.php" ) ){
		include( $path . "/$filename.php" );
	}else if( file_exists( ANTON_FEM_TEMPLATE_PATH . "/$filename.php" ) ){
		include( ANTON_FEM_TEMPLATE_PATH . "/$filename.php" );
	}else{
		printf( '<h4 class="%s">%s</h4>', anton_fem( 'center' ), __( 'Please select a template for your Featured Events Manager.', ANTON_FEM ) );
	}
}

function anton_fem_checkbox_pages( $args = '' ) {
	$defaults = array(
		'depth' => 0,
		'child_of' => 0,
		'selected' => 0,
		'echo' => 1,
		'name' => 'page_id',
		'id' => '',
		'class' => '',
		'show_option_none' => '',
		'show_option_no_change' => '',
		'option_none_value' => '',
		'value_field' => 'ID',
	);

	$r = wp_parse_args( $args, $defaults );
	$pages = get_pages( $r );
	$output = '';
	if ( empty( $r['id'] ) ) {
		$r['id'] = $r['name'];
	}
	if ( ! empty( $pages ) ) {
		$class = '';
		if ( ! empty( $r['class'] ) ) {
			$class = " class='" . esc_attr( $r['class'] ) . "'";
		}
		$output = "<span " . $class . " id='" . esc_attr( $r['id'] ) . "' class='" . anton_fem( 'checkbox' ) . "'>\n";
		$output .= anton_fem_walk_page_checkbox_tree( $pages, $r['depth'], $r );
		$output .= "</span>\n";
	}
	$html = apply_filters( 'anton_femc_checkbox_pages', $output, $r, $pages );
	if ( $r['echo'] ) {
		echo $html;
	}
	return $html;
}

function anton_fem_walk_page_checkbox_tree() {
	$args = func_get_args();
	if ( empty($args[2]['walker']) ) :
		$walker = new Anton_Feature_Events_Manager_Walker_Page_Checkbox;
	else :
		$walker = $args[2]['walker'];
	endif;
	return call_user_func_array(array($walker, 'walk'), $args);
}

function anton_fem_get_styles( $selector, $styles, $echo = false ){
	$styles = array_filter( $styles );
	if( $echo ){
		$all = '';
		if( $styles ){$all .= "$selector{";}
		foreach( $styles as $style ){
			if( ! empty( $style ) ){
				$all .= $style;
			}
		}
		if( $styles ){$all .= "}";}
		return $all;
	}
	if( $styles ){echo "$selector{";}
	foreach( $styles as $style ){
		if( ! empty( $style ) ){
			echo $style;
		}
	}
	if( $styles ){echo "}";}
}

function anton_fem_pixels_css(){
	return array( 'font-size', 'border-width', 'border-top-width', 'border-bottom-width', 'border-left-width', 'border-right-width', 'width', 'height', 'padding', 'padding-top', 'padding-bottom', 'padding-left', 'padding-right', 'margin', 'margin-top', 'margin-bottom', 'margin-left', 'margin-right', 'line-height' );
}

function anton_fem_get_css( $properties, $value ){
	$pixels = anton_fem_pixels_css();
	if(
		empty( $value ) && 
		in_array( $properties, array( 'border-top-color', 'border-bottom-color', 'border-left-color', 'border-right-color', 'border-color', 'background-color', 'color' ) )
	){
		#$value = 'transparent';
	}
	if( empty( $value ) && ! in_array( $properties, $pixels ) ){
		return;	
	}
	if( $properties == 'background-image' ){
		return "{$properties}:url('{$value}');";
	}
	$default = array_merge( $pixels, array( 'font-family' ) );
	if( in_array( $properties, $pixels ) && ( $value != '' ) ){
		return "{$properties}:{$value}px;";
	}
	if( ! in_array( $properties, $default ) ){
		return "{$properties}:{$value};";
	}
	if( $properties == 'font-family' ){
		return "{$properties}:'{$value}';";
	}
}

function anton_fem_truncate_phrase( $text, $max_characters ) {
	if ( ! $max_characters ) {
		return '';
	}
	$text = trim( $text );
	if ( mb_strlen( $text ) > $max_characters ) {
		$text = mb_substr( $text, 0, $max_characters + 1 );
		$text_trim = trim( mb_substr( $text, 0, mb_strrpos( $text, ' ' ) ) );
		$text = empty( $text_trim ) ? $text : $text_trim;
	}
	return $text;
}

function anton_fem_get_the_content( $max_characters, $more_link_text = '', $stripteaser = false ) {
	$content = get_the_content( '', $stripteaser );
	$content = strip_tags( strip_shortcodes( $content ), apply_filters( 'anton_fem_get_the_content_allowedtags', '<script>,<style>' ) );
	$content = trim( preg_replace( '#<(s(cript|tyle)).*?</\1>#si', '', $content ) );
	$content = anton_fem_truncate_phrase( $content, $max_characters );
	if ( $more_link_text ) {
		$link   = apply_filters( 'anton_fem_get_the_content_more_link', sprintf( '&#x02026; <a href="%s" class="more-link">%s</a>', get_permalink(), $more_link_text ), $more_link_text );
		$output = sprintf( '<p>%s %s</p>', $content, $link );
	} else {
		$output = sprintf( '<p>%s</p>', $content );
		$link = '';
	}
	$link = '';
	return apply_filters( 'anton_fem_get_the_content', $output, $content, $link, $max_characters );
}

function anton_fem_the_content( $max_characters, $more_link_text = '', $stripteaser = false ) {
	$content = anton_fem_get_the_content( $max_characters, $more_link_text, $stripteaser );
	echo apply_filters( 'anton_fem_the_content', $content );
}

function anton_fem_content_width() {
 	$options = get_option( 'genesis-settings' );
 	$output = ! empty( $options['content_width'] ) ? $options['content_width'] : 1140;
	return $output;
}

function anton_fem_get_option( $key, $echo = false ) {
 	$options = get_option( ANTON_FEM );
 	$output = ! empty( $options[$key] ) ? $options[$key] : '';
	if( $echo ) :
		echo $output;
		return;
	endif;
	return $output;
}

function anton_fem_field_name( $name, $echo = false ){
	if( $echo ) :
		echo ANTON_FEM . "[$name]";
		return;
	endif;
	return ANTON_FEM . "[$name]";
}

function anton_fem_attr_output( $value ){
	echo isset($value) ? $value : '';
}