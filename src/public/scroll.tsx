window.addEventListener( 'scroll', () => {
	const scroll = window.pageYOffset / ( document.body.offsetHeight - window.innerHeight );

	document.body.style.setProperty(
		'--scroll',
		scroll.toFixed( 2 ).toString()
	);
}, false );
