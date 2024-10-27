jQuery( document ).ready( function( $ ){
	var timeout = ( function(){
 		var timers = {};
 		return function( callback, ms, x_id ){
		   if ( !x_id ){ x_id = ''; }
		   if ( timers[x_id] ){ clearTimeout( timers[x_id] ); }
		   timers[x_id] = setTimeout( callback, ms );
 		};
	})(), anton_fem_upload, input_id, img_id,wpadminbar,adminmenuwrap,widget_id,widget_title,loading,has_anton, dataid;
	$( document ).delegate( id( 'featured' ), 'click', function(){
		featured = 1,
		dataid = $( this ).attr( 'data-id' );
		if( $( this ).hasClass( 'anton-fem-featured-active' ) ){
			featured = 0;
		}
		$.ajax({
			type	: "POST",
			url		: anton_fem.ajaxurl,
			data	: {
				action		: 'anton_fem_ajax',
				post_id		: dataid,
				featured	: featured,
			},
			beforeSend: function( response ) {
				$( c( 'featured-' + dataid ) ).html('<img class="' + slug( 'spinnir' ) + '" src="' + anton_fem.spinnir + '" alt="" />');
			},
			success: function( response ){
				$( c( 'featured-' + dataid ) ).parent().html( response );
			},		
		});	
	});
	$( this ).delegate( id( 'edit' ), 'click', function(){
		widget_id = '#' + $( this ).closest( '.widget' ).attr( 'id' );
		widget_title = $( this ).hasClass( 'widget-title' );
		wpadminbar = $( '#wpadminbar' ).outerHeight();
		adminmenuwrap = $( '#adminmenuwrap' ).outerWidth();
		$( widget_id + ' ' + id( 'fields' ) + ' ' + c( 'group' ) ).css({
			'height' : ( window.innerHeight - ( wpadminbar + 80 ) )
		});
		$( widget_id + ' ' + id( 'fields' ) ).css({
			'width' : ( window.innerWidth - adminmenuwrap ), 
			'top' : wpadminbar, 
			'left' : adminmenuwrap, 
			'height' : ( window.innerHeight - wpadminbar )
		});
		timeout( function(){
			if(
				( $( widget_id + ' ' + id( 'fields' ) ).closest( '.widget-inside' ).css( 'display' ) == 'block' ) &&
				( $( widget_id + ' ' + id( 'fields' ) ).css( 'display' ) == 'none' ) &&
				widget_title == false
			){
				$( widget_id ).addClass( slug( 'relative' ) ).addClass( slug( 'active' ) );
				$( widget_id + ' ' + id( 'fields' ) ).fadeIn( 'slow', function(){
					color_picker();
				});
				widget_title = true;
			}
		}, 300 );
	});
	$( window ).resize(function() {
		$( widget_id + ' ' + id( 'fields' ) + ' ' + c( 'group' ) ).css({
			'height' : ( window.innerHeight - ( wpadminbar + 80 ) )
		});
		$( widget_id + ' ' + id( 'fields' ) ).css({
			'width' : ( window.innerWidth - adminmenuwrap ), 
			'top' : wpadminbar, 
			'left' : adminmenuwrap, 
			'height' : ( window.innerHeight - wpadminbar )
		});
	});
	$( this ).delegate( id( 'fields' ) + ' ' + id( 'close' ), 'click', function(){
		timeout( function(){
			if( $( widget_id + ' ' + id( 'fields' ) ).closest( '.widget-inside' ).css( 'display' ) == 'block' ){
				$( widget_id ).removeClass( slug( 'relative' ) ).removeClass( slug( 'active' ) );
				$( widget_id + ' ' + id( 'fields' ) ).fadeOut( 'slow' );
			}
		}, 300 );
	});
	$( this ).delegate( '.widget-control-actions .button-primary', 'click', function(){
		has_anton = $( this ).closest( '.widgets-holder-wrap' ).find( c( 'group'  ) ).length;
	});
	$( this ).delegate( id( 'fields' ) + ' ' + c( 'advance' ), 'click', function(){
		$( this ).toggleClass( slug( 'advance-active' ) );
		$( id( 'fields' ) + ' ' + $( this ).attr( 'data-id' ) ).slideToggle();
		color_picker();
	});
	$( this ).delegate( id( 'upload' ), 'click', function(){
		input_id = $( this ).attr( 'data-id' ) + '-input';
		img_id = $( this ).attr( 'data-id' ) + '-img';
		if( anton_fem_upload ) {
			anton_fem_upload.open();
			return;
		}
		anton_fem_upload = wp.media.frames.file_frame = wp.media({
			title: 'Choose an image',
			button: { text: 'Choose image' },
			multiple: false,
			library : { type : 'image' }
		});
		anton_fem_upload.on( 'select', function() {
			attachment = anton_fem_upload.state().get( 'selection' ).first().toJSON();
			$( input_id ).val( attachment.id );
			$( img_id ).attr( 'src', attachment.url );
		});
		anton_fem_upload.open();
	});
	$( this ).ajaxStop( function() {
 		//color_picker();
	});
	function color_picker(){
		if( $( c( 'column-box' ) + ' ' + c( 'color-picker' ) ).length  ){
			Color.prototype.toString = function() {
				if (this._alpha < 1) {
					return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
				}
				var hex = parseInt(this._color, 10).toString(16);
				if (this.error) return '';
				if (hex.length < 6) {
					for (var i = 6 - hex.length - 1; i >= 0; i--) {
						hex = '0' + hex;
					}
				}
				return '#' + hex;
			};
			$( c( 'column-box' ) + ' ' + c( 'color-picker' ) ).each(function( index ) {
				if( ! $( this ).parent().hasClass( 'wp-picker-input-wrap' ) ){
					var $control = $(this),
						value = $control.val().replace(/\s+/g, ''),
						alpha_val = 100,
						$alpha, $alpha_output;
					if (value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)) {
						alpha_val = parseFloat(value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)[1]) * 100;
					}
					$control.wpColorPicker({
						clear: function(event, ui) {
							$alpha.val(100);
							$alpha_output.val(100 + '%');
						}
					});
					if( ! $( this ).find( c( 'alpha-wrap' ) ).length ){
						$( '<span class="' + slug( 'alpha-wrap' ) + '" style="display:none;">' + '<label>Alpha: <output class="rangevalue">' + alpha_val + '%</output></label>' + '<input type="range" min="1" max="100" value="' + alpha_val + '" name="alpha" class="' + slug( 'alpha-field' ) + '">' + '</span>').appendTo($control.parents('.wp-picker-container:first').addClass( slug( 'color-picker-group' ) ).find('.wp-picker-holder'));
					}				
					$alpha = $control.parents('.wp-picker-container:first').find( c( 'alpha-field' ) );
					$alpha_output = $control.parents('.wp-picker-container:first').find( c( 'alpha-wrap' ) + ' output');
					$alpha.bind('change keyup', function() {
						var alpha_val = parseFloat($alpha.val()),
							iris = $control.data('a8cIris'),
							color_picker = $control.data('wpWpColorPicker');
						$alpha_output.val($alpha.val() + '%');
						iris._color._alpha = alpha_val / 100.0;
						$control.val(iris._color.toString());
						color_picker.toggler.css({
							backgroundColor: $control.val()
						});
					}).val(alpha_val).trigger('change');
				}
			});
		}
	}
	function c( classes ){
		var a = '';
		var b = classes.split( ' ' );
		for( x=1; x <= b.length; x++ ){ a = a + '.' + anton_fem['slug'] + '-' + b[(x-1)] + ' '; }
		return a;
	}
	function slug( text ){
		var a = '';
		var b = text.split( ' ' );
		for( x=1; x <= b.length; x++ ){ a = a + anton_fem['slug'] + '-' + b[(x-1)] + ' '; }
		return a;
	}
	function id( id ){
		var a = '';
		var b = id.split( ' ' );
		for( x=1; x <= b.length; x++ ){ a = a + '#' + anton_fem['slug'] + '-' + b[(x-1)] + ' '; }
		return a;
	}
});