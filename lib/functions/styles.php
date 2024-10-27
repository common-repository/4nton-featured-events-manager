<?php

global $anton_fem_widget;

$r = $anton_fem_widget;

if( $r['background_image'] && $r['enable_background_image'] ) :
	$r['default_bg'] = wp_get_attachment_image_url( $r['background_image'], 'full' );
endif;

if( false === $r['enable_background_image'] ) :
	$r['default_bg'] = '';
endif;

$inline_styles = '';

$inline_styles .= anton_fem_get_styles( 
	sprintf(
		'.%s', 
		anton_fem( $r['randum'] )
	), 
	array(
		anton_fem_get_css( 'background-image', $r['default_bg'] ),
		anton_fem_get_css( 'background-color', $r['background_color'] ),
	), 
	true
);
$inline_styles .= anton_fem_get_styles( 
	sprintf(
		'.%s .%s', 
		anton_fem( $r['randum'] ),
		anton_fem( 'title' )
	), 
	array(
		anton_fem_get_css( 'color', $r['heading_color'] ),
	), 
	true
);
$inline_styles .= anton_fem_get_styles( 
	sprintf(
		'.%s .%s:after', 
		anton_fem( $r['randum'] ),
		anton_fem( 'title' )
	), 
	array(
		anton_fem_get_css( 'border-top-color', $r['separator_color'] ),
	), 
	true
);
$inline_styles .= anton_fem_get_styles( 
	sprintf(
		'.%s .%s .button', 
		anton_fem( $r['randum'] ),
		anton_fem( 'more-wrap' )
	), 
	array(
		anton_fem_get_css( 'background-color', $r['view_more_background_color'] ),
		anton_fem_get_css( 'border-color', $r['view_more_border_color'] ),
		anton_fem_get_css( 'color', $r['view_more_text_color'] ),
		anton_fem_get_css( 'font-size', $r['view_more_font_size'] ),
		anton_fem_get_css( 'border-width', $r['view_more_border_width'] ),
		anton_fem_get_css( 'padding-right', $r['view_more_padding_left_right'] ),
		anton_fem_get_css( 'padding-left', $r['view_more_padding_left_right'] ),
		anton_fem_get_css( 'line-height', $r['view_more_height'] ),
	), 
	true
);
$smallwidth = $r['default_width'];
if( $r['small_box_width'] ){
	$smallwidth = $r['small_box_width'];
}
$smallheight = $r['default_height'];
if( $r['small_box_height'] ){
	$smallheight = $r['small_box_height'];
}
$largeheight = $r['default_height'];
if( $r['large_box_height'] ){
	$largeheight = $r['large_box_height'];
}
$inline_styles .= anton_fem_get_styles( 
	sprintf(
		'#%s .%s .%s.%s', 
		anton_fem( $r['layout'] ),
		anton_fem( 'item' ),
		anton_fem( 'small' ),
		anton_fem( 'thumb-' . $r['randum'] )
	), 
	array(
		anton_fem_get_css( 'width', $smallwidth ),
		anton_fem_get_css( 'height', $smallheight ),
	), 
	true
);
$inline_styles .= anton_fem_get_styles( 
	sprintf(
		'#%s .%s .%s.%s', 
		anton_fem( $r['layout'] ),
		anton_fem( 'item' ),
		anton_fem( 'large' ),
		anton_fem( 'thumb-' . $r['randum'] )
	), 
	array(
		anton_fem_get_css( 'width', $r['large_box_width'] ),
		anton_fem_get_css( 'height', $largeheight ),
	), 
	true
);
$inline_styles .= anton_fem_get_styles( 
	sprintf(
		'.%s .%s .button:hover', 
		anton_fem( $r['randum'] ),
		anton_fem( 'more-wrap' )
	), 
	array(
		anton_fem_get_css( 'background-color', $r['view_more_hover_background_color'] ),
		anton_fem_get_css( 'border-color', $r['view_more_hover_border_color'] ),
		anton_fem_get_css( 'color', $r['view_more_hover_text_color'] )
	), 
	true
);
$inline_styles .= anton_fem_get_styles( 
	sprintf(
		'#%s .%s', 
		anton_fem( $r['layout'] ),
		anton_fem( 'item-title-' . $r['randum'] )
	), 
	array(
		anton_fem_get_css( 'color', $r['event_title_text_color'] ),
		anton_fem_get_css( 'font-size', $r['event_title_font_size'] ),
	), 
	true
);
$inline_styles .= anton_fem_get_styles( 
	sprintf(
		'.%s', 
		anton_fem( $r['randum'] )
	), 
	array(
		anton_fem_get_css( 'padding-top', $r['padding_top'] ),
		anton_fem_get_css( 'padding-bottom', $r['padding_bottom'] ),
	), 
	true
);
$inline_styles .= anton_fem_get_styles( 
	sprintf(
		'#%s .%s .%s', 
		anton_fem( $r['layout'] ),
		anton_fem( 'item' ),
		anton_fem( 'thumb-' . $r['randum'] )
	), 
	array(
		anton_fem_get_css( 'margin-bottom', $r['box_space'] ),
	), 
	true
);
$inline_styles .= anton_fem_get_styles( 
	sprintf(
		'.%s .%s', 
		anton_fem( $r['randum'] ),
		anton_fem( 'column' )
	),
	array(
		anton_fem_get_css( 'padding-right', ( $r['box_space'] / 2 ) ),
		anton_fem_get_css( 'padding-left', ( $r['box_space'] / 2 ) ),
	), 
	true
);
if( $r['box_space'] != '' ){
	$inline_styles .= anton_fem_get_styles( 
		sprintf(
			'.%s .%s', 
			anton_fem( $r['randum'] ),
			anton_fem( 'column-wrap' )
		),
		array(
			anton_fem_get_css( 'margin-right', '-' . ( $r['box_space'] / 2 ) ),
			anton_fem_get_css( 'margin-left', '-' . ( $r['box_space'] / 2 ) ),
		), 
		true
	);
}
printf( '<style>%s</style>', $inline_styles );