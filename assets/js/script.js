jQuery( document ).ready( function( $ ){ 
	var sample;
	if( $( '.anton-fem-wrap' ).length && anton_fem['inline_css']['content_width'] ){
		$( '.anton-fem-wrap' ).width( anton_fem['inline_css']['content_width'] ).animate({opacity:1},500);
	}
	if( $( '.anton-tws-layout-1' ).length ){
		$( '.anton-tws-layout-1' ).bxSlider({
			mode: 'fade',
			responsive: true,
			auto: true,
			autoStart: true,
			pause: 8000,
			controls: false,
			adaptiveHeight: true
		});
	}
	if( $( '.anton-tws-layout-2' ).length ){
		$( '.anton-tws-layout-2' ).bxSlider({
			auto: true, 
			autoStart: true, 
			pause: 8000, 
			controls: false, 
			adaptiveHeight: true ,
			slideWidth: ( ( $( '.anton-tws-dummy' ).width() - 60 ) / $( '.anton-tws-layout-2' ).attr( 'data-column' ) ),
			minSlides: 1,
			maxSlides: $( '.anton-tws-layout-2' ).attr( 'data-column' ),
			slideMargin: 20,
		});
	}
	if( $( '.anton-bsw-layout-1' ).length ){
		$( '.anton-bsw-layout-1 .anton-bsw-item' ).height( $( '#anton-bsw-layout-1' ).height() );
		$( '.anton-bsw-layout-1' ).bxSlider({
			mode: 'fade',
			auto: true,
			autoStart: true,
			pause: 8000,
			controls: false
		});
		$( '.anton-bsw-layout-1 .anton-bsw-item' ).css( 'opacity', 1 );
	}
	
	function anton( text ){
		return anton_fem['slug'] + '-' + text;
	}
});