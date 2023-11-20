let lastKnownPosition = 0;
let ticking = false;

window.addEventListener( 'scroll', function() {
	lastKnownPosition = window.scrollY;

	if ( ! ticking ) {
		window.requestAnimationFrame( function() {
			const scroll = lastKnownPosition / ( document.body.offsetHeight - window.innerHeight );
			document.body.style.setProperty( '--scroll', scroll.toFixed( 2 ).toString() );
			ticking = false;
		} );

		ticking = true;
	}
} );
