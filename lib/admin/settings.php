<?php

if ( !defined('ABSPATH') ){ exit; }

function anton_fem_settings_html() {
	?>
    <div id="anton-fem-settings">
        <div class="group">
            <form method="post" action="options.php">
                <input type="hidden" name="reset" id="reset-option" value="">
                <?php settings_fields( ANTON_FEM ); ?>
                <h1><?php _e( ANTON_FEM_NAME . ' Settings' ); ?></h1> 
<?php
	if ( isset( $_REQUEST['settings-updated'] ) == true ) { 
		echo '<div class="notice notice-success">';
		if ( isset( $_REQUEST['reset'] ) == true ) { 
       		printf( '<strong>%s</strong>', __( 'Options reset', ANTON_FEM ) );
		}else{
       		printf( '<strong>%s</strong>', __( 'Options saved', ANTON_FEM ) );
		}
        echo '<span class="dashicons dashicons-yes" style="color:#46b450;"></span>';
        echo '</div>';
	}
	if ( isset( $_REQUEST['reset'] ) == true ) { 
		delete_option( ANTON_FEM );
	}
	echo '<div class="anton-fem-toggle-content-first">';
	
	// Fields HTML output
	anton_fem_field_html(
		'number',
		'content_width',
		array(
			'label' => __( 'Content Width', ANTON_FEM ),
			'default' => anton_fem_content_width()
		)
	);
	
	echo '</div>';
	echo '<div class="anton-fem-table anton-fem-footer">';
	echo '<div class="submit-wrap align-left anton-fem-cell">';
//	printf(
//		'<input name="submit" id="reset" class="transition button" value="%s" type="submit">',
//		__( 'Reset', ANTON_FEM )
//	);
	echo '</div>';
	printf(
		'<div class="author anton-fem-center anton-fem-table-cell">%s <a href="%s"><strong>%s</strong></a></div>',
		__( 'Develop by', ANTON_FEM ),
		'https://www.anthonycarbon.com/',
		'Anthony Carbon'
	);
	echo '<div class="submit-wrap anton-fem-table-cell anton-fem-text-right">';
	printf(
		'<input name="submit" id="submit" class="transition button" value="%s" type="submit">',
		__( 'Save Changes', ANTON_FEM )
	);
 	echo '</div></div>';
	echo '</div></div>';
}

function anton_fem_field_html( $type, $key = null, $field = null ){
	$defaults = array(
		'min' => '',
		'max' => '',
		'label' => '',
		'default' => '',
		'description' => '',
	);
	$args = wp_parse_args( (array) $field, $defaults );
	switch ( $type ){
		case 'text' :
			?><label><?php anton_fem_attr_output( $args['label'] ); ?> <input 
                type="text" 
            	name="<?php anton_fem_field_name( $key, true ); ?>" 
                id="<?php echo $key; ?>"
                value="<?php anton_fem_get_option( $key, true ); ?>"
                placeholder="<?php anton_fem_attr_output( $args['default'] ); ?>"
		 	/></label><?php
			break;
		case 'url' :
			?><label><?php anton_fem_attr_output( $args['label'] ); ?> <input 
                type="url" 
            	name="<?php anton_fem_field_name( $key, true ); ?>" 
                id="<?php echo $key; ?>"
                value="<?php anton_fem_get_option( $key, true ); ?>"
                placeholder="<?php anton_fem_attr_output( $args['default'] ); ?>"
		 	/></label><?php
			break;
		case 'number' :
			?><label><?php anton_fem_attr_output( $args['label'] ); ?> <input 
                type="number" 
            	name="<?php anton_fem_field_name( $key, true ); ?>" 
                id="<?php echo $key; ?>"
                value="<?php anton_fem_get_option( $key, true ); ?>"
                min="<?php anton_fem_attr_output( $args['min'] ); ?>" 
                max="<?php anton_fem_attr_output( $args['max'] ); ?>" 
                placeholder="<?php anton_fem_attr_output( $args['default'] ); ?>"
		 	/> px</label><?php
			break;
		case 'checkbox' :
			?><label><input 
                type="checkbox" 
            	name="<?php anton_fem_field_name( $key, true ); ?>" 
                id="<?php echo $key; ?>"
                value="<?php anton_fem_get_option( $key, true )? anton_fem_get_option( $key ): '1'; ?>"
                <?php checked( anton_fem_get_option( $key ), 1 ); ?> 
		 	/> <?php anton_fem_attr_output( $args['label'] ); ?></label><?php
			break;
		case 'radio' :
			?><label><?php anton_fem_attr_output( $args['label'] ); ?> 
             	<?php foreach( $args['choices'] as $id => $option ) : ?>
                    <input 
                        type="radio" 
                        name="<?php anton_fem_field_name( $key, true ); ?>" 
                        id="<?php echo $key; ?>"
                        value="<?php echo $id; ?>"
                		<?php checked( anton_fem_get_option( $key ), $id ); ?> 
		 			/> <?php echo $option; ?>
            	<?php endforeach; ?>
            </label><?php
			break;
		case 'color' :
			?><label><span style="vertical-align:middle;"><?php anton_fem_attr_output( $args['label'] ); ?></span> <input 
                type="text" 
            	name="<?php anton_fem_field_name( $key, true ); ?>" 
                id="<?php echo $key; ?>"
                class="<?php anton_fem_e( 'color-picker' ); ?>"
                value="<?php anton_fem_get_option( $key, true ); ?>"
                placeholder="<?php anton_fem_attr_output( $args['default'] ); ?>"
		 	/></label><?php
			break;
		case 'select' :
			?><label><?php anton_fem_attr_output( $args['label'] ); ?> 
            <select 
            	name="<?php anton_fem_field_name( $key, true ); ?>" 
                id="<?php echo $key; ?>"
           	>
            	<option value="" <?php selected( anton_fem_get_option( $key ), '' ); ?>>Select Options</option>
             	<?php foreach( $args['choices'] as $id => $option ) : ?>
                	<option value="<?php echo $id; ?>" <?php selected( anton_fem_get_option( $key ), $id ); ?>><?php echo $option; ?></option>
            	<?php endforeach; ?>
            </select></label><?php
			break;
		case 'textarea' :
			?><label style="    display: block;"><?php anton_fem_attr_output( $args['label'] ); ?> <br /><textarea 
                name="<?php anton_fem_field_name( $key, true ); ?>" 
                id="<?php echo $key; ?>>" 
                cols="100" 
                rows="8"
                placeholder="<?php anton_fem_attr_output( $args['default'] ); ?>"
                ><?php echo esc_textarea( anton_fem_get_option( $key ) ); ?></textarea><br />
            <?php if( isset( $args['description'] ) ) : ?>
            	<i class="description"><?php anton_fem_attr_output( $args['description'] ); ?></i>
            <?php endif; ?>
            </label><?php
			break;
		default :
			return;
			break;	
	}
}