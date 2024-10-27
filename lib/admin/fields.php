<?php

if ( !defined('ABSPATH') ){ exit; }

function anton_fem_edit_fields( $instance, $this, $defaults, $textdomain, $others = false ){
	global $pagenow;
	$instance = wp_parse_args(
		(array) $instance,
		array(
			'background_image' => '',
			'default_thumbnail' => '',
			'thumbnail' => '',
			'enable_background_image' => '',
			'default_bg' => '',
			'description' => '',
			'page_ids' => '',
			'heading_color' => '',
		)
	);
	if( $instance['background_image'] ) :
		$instance['default_bg'] = wp_get_attachment_image_url( $instance['background_image'], 'full' );
	endif;
	if( $instance['thumbnail'] ) :
		$instance['default_thumbnail'] = wp_get_attachment_image_url( $instance['thumbnail'], 'thumbnail' );
	endif;
	$enable_background_image = (bool) $instance['enable_background_image'];
	?>
    <?php /*if( $pagenow == 'widgets.php' ) : ?>
    <div id="<?php anton_fem_e( 'edit' ); ?>"><span><?php _e( 'CLICK TO EDIT FIELDS', $textdomain ); ?> </span></div>
    <div id="<?php anton_fem_e( 'fields' ); ?>" class="<?php anton_fem_e( 'fix' ); ?>" style="display:none;">
    <?php else :*/ ?>
    <div id="<?php anton_fem_e( 'fields' ); ?>">
    <?php /*endif;*/ ?>
    <div class="<?php anton_fem_e( 'group' ); ?>">
	<?php if( in_array( 'title', $defaults ) ) : ?>
		<p>
			<label for="<?php echo $this['title']['id']; ?>"><?php _e( 'Title', $textdomain ); ?> : </label>
			<input class="widefat" id="<?php echo $this['title']['id']; ?>" name="<?php echo $this['title']['name']; ?>" type="text" value="<?php echo esc_attr( sanitize_text_field( $instance['title'] ) ); ?>" />
		</p>
	<?php endif; ?>
	<?php if( in_array( 'description', $defaults ) ) : ?>
		<p>
			<label for="<?php echo $this['description']['id']; ?>"><?php _e( 'Description', $textdomain ); ?> : </label><br />
            
			<textarea class="widefat" rows="5" cols="20" id="<?php echo $this['description']['id']; ?>" name="<?php echo $this['description']['name']; ?>"><?php echo esc_textarea( $instance['description'] ); ?></textarea>
		</p>
	<?php endif; ?>
	<?php if( in_array( 'layout', $defaults ) ) : ?>
        <p>
            <label for="<?php echo esc_attr( $this['layout']['id'] ); ?>"><?php _e( 'Layout', $textdomain ); ?> : </label>
            <select id="<?php echo esc_attr( $this['layout']['id'] ); ?>" name="<?php echo esc_attr( $this['layout']['name'] ); ?>">
                <?php if( ! empty( $others['layout'] ) ) : ?>
                    <?php foreach( $others['layout'] as $key => $option ){ ?>
                        <option value="<?php echo $key; ?>" <?php selected( $key, $instance['layout'] ); ?>><?php echo $option; ?></option>
                    <?php } ?>
                <?php else : ?>
                    <option value="" <?php selected( "", $instance['layout'] ); ?>><?php _e( "No Available Layouts", $textdomain ); ?></option>
                <?php endif; ?>
            </select>
            <a href="https://www.anthonycarbon.com/product-category/anton-featured-events-manager/" target="_blank"><i><?php _e( 'Check our available layouts here.', $textdomain ); ?></i></a>
        </p>
    <?php endif; ?>
    <div class="<?php anton_fem_e( 'advance' ); ?>" data-id="#<?php anton_fem_e( 'events-' . $instance['widget_id'] ); ?>"><span class="<?php anton_fem_e( 'left' ); ?>"><?php _e( 'Event Settings', $textdomain ); ?></span><span class="<?php anton_fem_e( 'right' ); ?>"></span></div>
	<div id="<?php anton_fem_e( 'events-' . $instance['widget_id'] ); ?>" class="<?php anton_fem_e( 'column-box' ); ?>" style="display:none;">
		<?php if( in_array( 'page_ids', $defaults ) ) : ?>
			<p>
				<label for="<?php echo $this['page_ids']['id']; ?>"><?php _e( 'Select pages where to display this section.', $textdomain ); ?> : </label>
				<?php
					anton_fem_checkbox_pages( array(
						'name'     => esc_attr( $this['page_ids']['name'] ),
						'id'       => $this['page_ids']['id'],
						'exclude'  => get_option( 'page_for_posts' ),
						'selected' => (array) $instance['page_ids'],
					) );
				?>
			</p>
    	<?php endif; ?>
		<?php if( in_array( 'thumbnail', $defaults ) ) : ?>
			<div>
				<label for="<?php echo $this['thumbnail']['id']; ?>"><?php _e( 'Default Thumbnail Image', $textdomain ); ?> : </label>
				<div id="<?php anton_fem_e( 'upload' ); ?>" class="<?php anton_fem_e( 'center' ); ?>" data-id="#<?php echo $this['thumbnail']['id']; ?>">
					<img id="<?php echo $this['thumbnail']['id']; ?>-img" src="<?php echo $instance['default_thumbnail']; ?>" style=" max-width: 100%; " />
				</div>
				<input id="<?php echo $this['thumbnail']['id']; ?>-input" name="<?php echo $this['thumbnail']['name']; ?>" type="hidden" value="<?php echo absint( $instance['thumbnail'] ); ?>" />       
			</div>
    	<?php endif; ?>
		<?php if( in_array( 'category', $defaults ) ) : ?>
			<p>
				<label for="<?php echo $this['category']['id']; ?>"><?php _e( 'Category', $textdomain ); ?>:</label>
				<?php
					$categories_args = array(
						'name'            	=> $this['category']['name'],
						'id'              	=> $this['category']['id'],
						'selected'        	=> $instance['category'],
						'orderby'         	=> 'Name',
						'hierarchical'    	=> 1,
						'show_option_all' 	=> __( 'All Categories', $textdomain ),
						'taxonomy'   		=> isset( $others['taxonomy'] ) ? $others['taxonomy'] : '',
					);
					wp_dropdown_categories( $categories_args );
				?>
			</p>
    	<?php endif; ?>
		<?php if( in_array( 'posts_num', $defaults ) ) : ?>
			<p>
				<label for="<?php echo $this['posts_num']['id']; ?>"><?php _e( 'Post Per Page', $textdomain ); ?> : </label>
				<input class="tiny-text" id="<?php echo $this['posts_num']['id']; ?>" name="<?php echo $this['posts_num']['name']; ?>" type="number" step="1" min="1" value="<?php echo absint( $instance['posts_num'] ); ?>" size="3" />
			</p>
    	<?php endif; ?>
		<?php if( in_array( 'content_limit', $defaults ) ) : ?>
			<p>
				<label for="<?php echo $this['content_limit']['id']; ?>"><?php _e( 'Content Limit', $textdomain ); ?> : </label>
				<input id="<?php echo $this['content_limit']['id']; ?>" name="<?php echo $this['content_limit']['name']; ?>" type="number" step="1" min="1" value="<?php echo absint( $instance['content_limit'] ); ?>" />
			</p>
    	<?php endif; ?>
		<?php if( in_array( 'column', $defaults ) ) : ?>
			<p>
				<label for="<?php echo esc_attr( $this['column']['id'] ); ?>"><?php _e( 'Column', $textdomain ); ?> : </label>
				<select id="<?php echo esc_attr( $this['column']['id'] ); ?>" name="<?php echo esc_attr( $this['column']['name'] ); ?>">
					<option value="1" <?php selected( '1', $instance['column'] ); ?>><?php _e( '1', $textdomain ); ?></option>
					<option value="2" <?php selected( '2', $instance['column'] ); ?>><?php _e( '2', $textdomain ); ?></option>
					<option value="3" <?php selected( '3', $instance['column'] ); ?>><?php _e( '3', $textdomain ); ?></option>
					<option value="4" <?php selected( '4', $instance['column'] ); ?>><?php _e( '4', $textdomain ); ?></option>
					<option value="5" <?php selected( '5', $instance['column'] ); ?>><?php _e( '5', $textdomain ); ?></option>
					<option value="6" <?php selected( '6', $instance['column'] ); ?>><?php _e( '6', $textdomain ); ?></option>
				</select>
			</p>
    	<?php endif; ?>
		<?php if( in_array( 'read_more_text', $defaults ) ) : ?>
			<p>
				<label for="<?php echo $this['read_more_text']['id']; ?>"><?php _e( 'Read More Button Text' ); ?> : </label>
				<input 
					type="url" 
					name="<?php echo $this['read_more_text']['name']; ?>" 
					id="<?php echo $this['read_more_text']['id']; ?>"
                    class="widefat"
					value="<?php echo $instance['read_more_text']; ?>"
                    placeholder="<?php _e( 'Leave it empty to disabled', $textdomain ); ?>"                   
				/>  
			</p>
    	<?php endif; ?>
		<?php if( in_array( 'view_more_text', $defaults ) ) : ?>
			<p>
				<label for="<?php echo $this['view_more_text']['id']; ?>"><?php _e( 'View More Button Text' ); ?> : </label>
				<input 
					type="url" 
					name="<?php echo $this['view_more_text']['name']; ?>" 
					id="<?php echo $this['view_more_text']['id']; ?>"
                    class="widefat"
					value="<?php echo $instance['view_more_text']; ?>"
                    placeholder="<?php _e( 'Leave it empty to disabled', $textdomain ); ?>"                   
				/>  
			</p>
    	<?php endif; ?>
		<?php if( in_array( 'view_more_url', $defaults ) ) : ?>
			<p>
				<label for="<?php echo $this['view_more_url']['id']; ?>"><?php _e( 'View More Button URL' ); ?> : </label>
				<input 
					type="url" 
					name="<?php echo $this['view_more_url']['name']; ?>" 
					id="<?php echo $this['view_more_url']['id']; ?>"
                    class="widefat"
					value="<?php echo $instance['view_more_url']; ?>"
                    placeholder="<?php _e( 'https://demo.anthonycarbon.com/', $textdomain ); ?>"                   
				/>  
			</p>
    	<?php endif; ?>
    </div>
    <div class="<?php anton_fem_e( 'advance' ); ?>" data-id="#<?php anton_fem_e( 'backgrounds-' . $instance['widget_id'] ); ?>"><span class="<?php anton_fem_e( 'left' ); ?>"><?php _e( 'Background Settings', $textdomain ); ?></span><span class="<?php anton_fem_e( 'right' ); ?>"></span></div>
	<div id="<?php anton_fem_e( 'backgrounds-' . $instance['widget_id'] ); ?>" class="<?php anton_fem_e( 'column-box' ); ?>" style="display:none;">
		<?php if( in_array( 'background_image', $defaults ) ) : ?>
			<div class="<?php anton_fem_e( 'bi-item field' ); ?>">
				<label for="<?php echo $this['background_image']['id']; ?>"><?php _e( 'Background Image', $textdomain ); ?> : </label>
				<div id="<?php anton_fem_e( 'upload' ); ?>" data-id="#<?php echo $this['background_image']['id']; ?>">
					<img id="<?php echo $this['background_image']['id']; ?>-img" src="<?php echo $instance['default_bg']; ?>" style=" max-width: 100%; " />
				</div>
				<input id="<?php echo $this['background_image']['id']; ?>-input" name="<?php echo $this['background_image']['name'] ?>" type="hidden" value="<?php echo absint( $instance['background_image'] ); ?>" />       
			</div>
    	<?php endif; ?>
		<?php if( in_array( 'enable_background_image', $defaults ) ) : ?>
			<p>
				<label for="<?php echo $this['enable_background_image']['id']; ?>">
					<input class="checkbox" type="checkbox"<?php checked( true, $enable_background_image ); ?> id="<?php echo $this['enable_background_image']['id']; ?>" name="<?php echo $this['enable_background_image']['name']; ?>" value="1" />
					<?php _e( 'Enable Background Image', $textdomain ); ?>
				</label>
			</p>
    	<?php endif; ?>
		<?php if( in_array( 'background_color', $defaults ) ) : ?>
			<div class="<?php anton_fem_e( 'color-item field' ); ?>">
				<label for="<?php echo $this['background_color']['id']; ?>"><?php _e( 'Background Color', $textdomain ); ?> : </label>
				<input 
					type="text" 
					name="<?php echo $this['background_color']['name']; ?>" 
					id="<?php echo $this['background_color']['id']; ?>" 
					class="<?php anton_fem_e( 'color-picker' ); ?>" 
					value="<?php echo $instance['background_color']; ?>"
					style="display:none;"                    
				/>  
			</div>
    	<?php endif; ?>
		<?php if( in_array( 'overlay_bc', $defaults ) ) : ?>
			<div class="<?php anton_fem_e( 'color-item field' ); ?>">
				<label for="<?php echo $this['overlay_bc']['id']; ?>"><?php _e( 'Overlay Background Color', $textdomain ); ?> : </label>
				<input 
					type="text" 
					name="<?php echo $this['overlay_bc']['name']; ?>" 
					id="<?php echo $this['overlay_bc']['id']; ?>" 
					class="<?php anton_fem_e( 'color-picker' ); ?>" 
					value="<?php echo $instance['overlay_bc']; ?>"
					style="display:none;"                    
				/>  
			</div>
    	<?php endif; ?>
		<?php if( in_array( 'view_more_background_color', $defaults ) ) : ?>
			<div class="<?php anton_fem_e( 'color-item field' ); ?>">
				<label for="<?php echo $this['view_more_background_color']['id']; ?>"><?php _e( 'View More Background Color', $textdomain ); ?> : </label>
				<input 
					type="text" 
					name="<?php echo $this['view_more_background_color']['name']; ?>" 
					id="<?php echo $this['view_more_background_color']['id']; ?>" 
					class="<?php anton_fem_e( 'color-picker' ); ?>" 
					value="<?php echo $instance['view_more_background_color']; ?>"
					style="display:none;"                    
				/>  
			</div>
    	<?php endif; ?>
		<?php if( in_array( 'view_more_hover_background_color', $defaults ) ) : ?>
			<div class="<?php anton_fem_e( 'color-item field' ); ?>">
				<label for="<?php echo $this['view_more_hover_background_color']['id']; ?>"><?php _e( 'View More Hover Background Color', $textdomain ); ?> : </label>
				<input 
					type="text" 
					name="<?php echo $this['view_more_hover_background_color']['name']; ?>" 
					id="<?php echo $this['view_more_hover_background_color']['id']; ?>" 
					class="<?php anton_fem_e( 'color-picker' ); ?>" 
					value="<?php echo $instance['view_more_hover_background_color']; ?>"
					style="display:none;"                    
				/>  
			</div>
    	<?php endif; ?>
    </div>
    <div class="<?php anton_fem_e( 'advance' ); ?>" data-id="#<?php anton_fem_e( 'colors-' . $instance['widget_id'] ); ?>"><span class="<?php anton_fem_e( 'left' ); ?>"><?php _e( 'Color Settings', $textdomain ); ?></span><span class="<?php anton_fem_e( 'right' ); ?>"></span></div>
	<div id="<?php anton_fem_e( 'colors-' . $instance['widget_id'] ); ?>" class="<?php anton_fem_e( 'column-box' ); ?>" style="display:none;">
		<?php if( in_array( 'heading_color', $defaults ) ) : ?>
			<div class="<?php anton_fem_e( 'color-item field' ); ?>">
				<label for="<?php echo $this['heading_color']['id']; ?>"><?php _e( 'Widget Title Text Color', $textdomain ); ?> : </label>
				<input 
					type="text" 
					name="<?php echo $this['heading_color']['name']; ?>" 
					id="<?php echo $this['heading_color']['id']; ?>" 
					class="<?php anton_fem_e( 'color-picker' ); ?>" 
					value="<?php echo $instance['heading_color']; ?>"
					style="display:none;"                    
				/>  
			</div>
    	<?php endif; ?>
		<?php if( in_array( 'event_title_text_color', $defaults ) ) : ?>
			<div class="<?php anton_fem_e( 'color-item field' ); ?>">
				<label for="<?php echo $this['event_title_text_color']['id']; ?>"><?php _e( 'Event Title Text Color', $textdomain ); ?> : </label>
				<input 
					type="text" 
					name="<?php echo $this['event_title_text_color']['name']; ?>" 
					id="<?php echo $this['event_title_text_color']['id']; ?>" 
					class="<?php anton_fem_e( 'color-picker' ); ?>" 
					value="<?php echo $instance['event_title_text_color']; ?>"
					style="display:none;"                    
				/>  
			</div>
    	<?php endif; ?>
		<?php if( in_array( 'separator_color', $defaults ) ) : ?>
			<div class="<?php anton_fem_e( 'color-item field' ); ?>">
				<label for="<?php echo $this['separator_color']['id']; ?>"><?php _e( 'Separator Color', $textdomain ); ?> : </label>
				<input 
					type="text" 
					name="<?php echo $this['separator_color']['name']; ?>" 
					id="<?php echo $this['separator_color']['id']; ?>" 
					class="<?php anton_fem_e( 'color-picker' ); ?>" 
					value="<?php echo $instance['separator_color']; ?>"
					style="display:none;"                    
				/>  
			</div>
    	<?php endif; ?>
		<?php if( in_array( 'color', $defaults ) ) : ?>
			<div class="<?php anton_fem_e( 'color-item field' ); ?>">
				<label for="<?php echo $this['color']['id']; ?>"><?php _e( 'Content Text Color', $textdomain ); ?> : </label>
				<input 
					type="text" 
					name="<?php echo $this['color']['name']; ?>" 
					id="<?php echo $this['color']['id']; ?>" 
					class="<?php anton_fem_e( 'color-picker' ); ?>" 
					value="<?php echo $instance['color']; ?>"
					style="display:none;"                    
				/>  
			</div>
    	<?php endif; ?>
		<?php if( in_array( 'view_more_border_color', $defaults ) ) : ?>
			<div class="<?php anton_fem_e( 'color-item field' ); ?>">
				<label for="<?php echo $this['view_more_border_color']['id']; ?>"><?php _e( 'View More Border Color', $textdomain ); ?> : </label>
				<input 
					type="text" 
					name="<?php echo $this['view_more_border_color']['name']; ?>" 
					id="<?php echo $this['view_more_border_color']['id']; ?>" 
					class="<?php anton_fem_e( 'color-picker' ); ?>" 
					value="<?php echo $instance['view_more_border_color']; ?>"
					style="display:none;"                    
				/>  
			</div>
    	<?php endif; ?>
		<?php if( in_array( 'view_more_hover_border_color', $defaults ) ) : ?>
			<div class="<?php anton_fem_e( 'color-item field' ); ?>">
				<label for="<?php echo $this['view_more_hover_border_color']['id']; ?>"><?php _e( 'View More Hover Border Color', $textdomain ); ?> : </label>
				<input 
					type="text" 
					name="<?php echo $this['view_more_hover_border_color']['name']; ?>" 
					id="<?php echo $this['view_more_hover_border_color']['id']; ?>" 
					class="<?php anton_fem_e( 'color-picker' ); ?>" 
					value="<?php echo $instance['view_more_hover_border_color']; ?>"
					style="display:none;"                    
				/>  
			</div>
    	<?php endif; ?>
		<?php if( in_array( 'view_more_text_color', $defaults ) ) : ?>
			<div class="<?php anton_fem_e( 'color-item field' ); ?>">
				<label for="<?php echo $this['view_more_text_color']['id']; ?>"><?php _e( 'View More Text Color', $textdomain ); ?> : </label>
				<input 
					type="text" 
					name="<?php echo $this['view_more_text_color']['name']; ?>" 
					id="<?php echo $this['view_more_text_color']['id']; ?>" 
					class="<?php anton_fem_e( 'color-picker' ); ?>" 
					value="<?php echo $instance['view_more_text_color']; ?>"
					style="display:none;"                    
				/>  
			</div>
    	<?php endif; ?>
		<?php if( in_array( 'view_more_hover_text_color', $defaults ) ) : ?>
			<div class="<?php anton_fem_e( 'color-item field' ); ?>">
				<label for="<?php echo $this['view_more_hover_text_color']['id']; ?>"><?php _e( 'View More Hover Text Color', $textdomain ); ?> : </label>
				<input 
					type="text" 
					name="<?php echo $this['view_more_hover_text_color']['name']; ?>" 
					id="<?php echo $this['view_more_hover_text_color']['id']; ?>" 
					class="<?php anton_fem_e( 'color-picker' ); ?>" 
					value="<?php echo $instance['view_more_hover_text_color']; ?>"
					style="display:none;"                    
				/>  
			</div>
    	<?php endif; ?>
    </div>
    <div class="<?php anton_fem_e( 'advance' ); ?>" data-id="#<?php anton_fem_e( 'sizes-' . $instance['widget_id'] ); ?>"><span class="<?php anton_fem_e( 'left' ); ?>"><?php _e( 'Size Settings', $textdomain ); ?></span><span class="<?php anton_fem_e( 'right' ); ?>"></span></div>
	<div id="<?php anton_fem_e( 'sizes-' . $instance['widget_id'] ); ?>" class="<?php anton_fem_e( 'column-box' ); ?>" style="display:none;">
		<?php if( in_array( 'event_title_font_size', $defaults ) ) : ?>
			<p>
				<label for="<?php echo $this['event_title_font_size']; ?>"><?php _e( 'Event Title Font Size', $textdomain ); ?> : </label>
				<input class="width-field" id="<?php echo $this['event_title_font_size']['id']; ?>" name="<?php echo $this['event_title_font_size']['name']; ?>" type="number" step="1" value="<?php echo $instance['event_title_font_size']; ?>" /> px
			</p>
    	<?php endif; ?>
		<?php if( in_array( 'default_width', $defaults ) ) : ?>
			<p>
				<label for="<?php echo $this['default_width']; ?>"><?php _e( 'Default Width', $textdomain ); ?> : </label>
				<input class="width-field" id="<?php echo $this['default_width']['id']; ?>" name="<?php echo $this['default_width']['name']; ?>" type="number" step="1" value="<?php echo $instance['default_width']; ?>" /> px
			</p>
    	<?php endif; ?>
		<?php if( in_array( 'large_box_width', $defaults ) ) : ?>
			<p>
				<label for="<?php echo $this['large_box_width']; ?>"><?php _e( 'Large Box Width', $textdomain ); ?> : </label>
				<input class="width-field" id="<?php echo $this['large_box_width']['id']; ?>" name="<?php echo $this['large_box_width']['name']; ?>" type="number" value="<?php echo $instance['large_box_width']; ?>" /> px
			</p>
    	<?php endif; ?>
		<?php if( in_array( 'small_box_width', $defaults ) ) : ?>
			<p>
				<label for="<?php echo $this['small_box_width']; ?>"><?php _e( 'Small Box Width', $textdomain ); ?> : </label>
				<input class="width-field" id="<?php echo $this['small_box_width']['id']; ?>" name="<?php echo $this['small_box_width']['name']; ?>" type="number" value="<?php echo $instance['small_box_width']; ?>" /> px
			</p>
    	<?php endif; ?>
		<?php if( in_array( 'default_height', $defaults ) ) : ?>
			<p>
				<label for="<?php echo $this['default_height']; ?>"><?php _e( 'Default Height', $textdomain ); ?> : </label>
				<input class="height-field" id="<?php echo $this['default_height']['id']; ?>" name="<?php echo $this['default_height']['name']; ?>" type="number" step="1" value="<?php echo $instance['default_height']; ?>" /> px
			</p>
    	<?php endif; ?>
		<?php if( in_array( 'large_box_height', $defaults ) ) : ?>
			<p>
				<label for="<?php echo $this['large_box_height']; ?>"><?php _e( 'Large Box Height', $textdomain ); ?> : </label>
				<input class="height-field" id="<?php echo $this['large_box_height']['id']; ?>" name="<?php echo $this['large_box_height']['name']; ?>" type="number" step="1" value="<?php echo $instance['large_box_height']; ?>" /> px
			</p>
    	<?php endif; ?>
		<?php if( in_array( 'small_box_height', $defaults ) ) : ?>
			<p>
				<label for="<?php echo $this['small_box_height']; ?>"><?php _e( 'Small Box Height', $textdomain ); ?> : </label>
				<input class="height-field" id="<?php echo $this['small_box_height']['id']; ?>" name="<?php echo $this['small_box_height']['name']; ?>" type="number" step="1" value="<?php echo $instance['small_box_height']; ?>" /> px
			</p>
    	<?php endif; ?>
		<?php if( in_array( 'view_more_font_size', $defaults ) ) : ?>
			<p>
				<label for="<?php echo $this['view_more_font_size']; ?>"><?php _e( 'View More Font Size', $textdomain ); ?> : </label>
				<input class="height-field" id="<?php echo $this['view_more_font_size']['id']; ?>" name="<?php echo $this['view_more_font_size']['name']; ?>" type="number" value="<?php echo $instance['view_more_font_size']; ?>" /> px
			</p>
    	<?php endif; ?>
		<?php if( in_array( 'view_more_border_width', $defaults ) ) : ?>
			<p>
				<label for="<?php echo $this['view_more_border_width']; ?>"><?php _e( 'View More Border Width', $textdomain ); ?> : </label>
				<input class="height-field" id="<?php echo $this['view_more_border_width']['id']; ?>" name="<?php echo $this['view_more_border_width']['name']; ?>" type="number" value="<?php echo $instance['view_more_border_width']; ?>" /> px
			</p>
    	<?php endif; ?>
		<?php if( in_array( 'view_more_padding_left_right', $defaults ) ) : ?>
			<p>
				<label for="<?php echo $this['view_more_padding_left_right']; ?>"><?php _e( 'View More Padding Left/Right', $textdomain ); ?> : </label>
				<input class="height-field" id="<?php echo $this['view_more_padding_left_right']['id']; ?>" name="<?php echo $this['view_more_padding_left_right']['name']; ?>" type="number" value="<?php echo $instance['view_more_padding_left_right']; ?>" /> px
			</p>
    	<?php endif; ?>
		<?php if( in_array( 'view_more_height', $defaults ) ) : ?>
			<p>
				<label for="<?php echo $this['view_more_height']; ?>"><?php _e( 'View More Height', $textdomain ); ?> : </label>
				<input class="height-field" id="<?php echo $this['view_more_height']['id']; ?>" name="<?php echo $this['view_more_height']['name']; ?>" type="number" value="<?php echo $instance['view_more_height']; ?>" /> px
			</p>
    	<?php endif; ?>
    </div>
    <div class="<?php anton_fem_e( 'advance' ); ?>" data-id="#<?php anton_fem_e( 'spacing-' . $instance['widget_id'] ); ?>"><span class="<?php anton_fem_e( 'left' ); ?>"><?php _e( 'Space Settings', $textdomain ); ?></span><span class="<?php anton_fem_e( 'right' ); ?>"></span></div>
	<div id="<?php anton_fem_e( 'spacing-' . $instance['widget_id'] ); ?>" class="<?php anton_fem_e( 'column-box' ); ?>" style="display:none;">
		<?php if( in_array( 'padding_top', $defaults ) ) : ?>
			<p>
				<label for="<?php echo $this['padding_top']; ?>"><?php _e( 'Padding Top', $textdomain ); ?> : </label>
				<input class="height-field" id="<?php echo $this['padding_top']['id']; ?>" name="<?php echo $this['padding_top']['name']; ?>" type="number" value="<?php echo $instance['padding_top']; ?>" /> px
			</p>
    	<?php endif; ?>
		<?php if( in_array( 'padding_bottom', $defaults ) ) : ?>
			<p>
				<label for="<?php echo $this['padding_top']; ?>"><?php _e( 'Padding Bottom', $textdomain ); ?> : </label>
				<input class="height-field" id="<?php echo $this['padding_bottom']['id']; ?>" name="<?php echo $this['padding_bottom']['name']; ?>" type="number" value="<?php echo $instance['padding_bottom']; ?>" /> px
			</p>
    	<?php endif; ?>
		<?php if( in_array( 'box_space', $defaults ) ) : ?>
			<p>
				<label for="<?php echo $this['box_space']; ?>"><?php _e( 'Box Space / Gutter', $textdomain ); ?> : </label>
				<input class="height-field" id="<?php echo $this['box_space']['id']; ?>" name="<?php echo $this['box_space']['name']; ?>" type="number" value="<?php echo $instance['box_space']; ?>" placeholder="30" /> px
			</p>
    	<?php endif; ?>
    </div>
 	<div id="<?php anton_fem_e( 'space' ); ?>" style="height:30px;"></div> 	
    <?php /*if( $pagenow == 'widgets.php' ) : ?>
 		<div id="<?php anton_fem_e( 'close' ); ?>" class="button">Close</div>
    <?php endif;*/ ?>
    </div>
    </div>
    <?php if( is_numeric( $instance['widget_id'] ) ) : ?>
		<div id="<?php anton_fem_e( 'shortcode' ); ?>"><strong>Widget Shortcode:</strong> <span>[anton-fem-widget id="<?php echo $instance['widget_id']; ?>"]</span></div>
    <?php endif; ?>
 	<?php
}