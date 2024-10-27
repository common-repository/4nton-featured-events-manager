<?php

class Anton_Feature_Events_Manager_Walker_Page_Checkbox extends Walker {
	public $tree_type = 'page';
	public $db_fields = array( 'parent' => 'post_parent', 'id' => 'ID' );
	public function start_el( &$output, $page, $depth = 0, $args = array(), $id = 0 ) {
		$pad = str_repeat('&nbsp;', $depth * 3);

		if ( ! isset( $args['value_field'] ) || ! isset( $page->{$args['value_field']} ) ) {
			$args['value_field'] = 'ID';
		}
		$checked = '';
		$selected = (array) $args['selected'] ? : array();
		if( in_array( $page->{$args['value_field']}, $selected ) ) :
			$checked = ' checked="checked"';
		endif;
		$output .= sprintf(
			'<label>%s<input type="checkbox" name="%s[]" class="%s" value="%s"%s>',
			$pad,
			esc_attr( $args['name'] ),
			"level-$depth",
			esc_attr( $page->{$args['value_field']} ),
			$checked // checked( esc_attr( $page->{$args['value_field']} ), $args['selected'], false )
		);
		$checked = '';
		$title = $page->post_title;
		if ( '' === $title ) {
			$title = sprintf( __( '#%d (no title)' ), $page->ID );
		}
		$title = apply_filters( 'anton_fem_list_pages', $title, $page );
		$output .= esc_html( $title );
		$output .= "</label>\n";
	}
}