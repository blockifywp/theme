( () => {
	const mediaQuery = window.matchMedia( "(prefers-reduced-motion: reduce)" );

	if ( ! mediaQuery || mediaQuery.matches ) {
		return;
	}

	const observer = new IntersectionObserver( entries => {
		entries.forEach( entry => {
			const block    = entry.target as HTMLElement;
			const infinite = block.style.animationIterationCount === 'infinite';

			if ( entry.isIntersecting && ! infinite ) {
				block.classList.add( 'animate' );
				block.style.opacity   = '0';
				block.style.transform = 'none';

				const duration = parseFloat( block?.style?.animationDuration?.replace( 's', '' ) ) * 1000 ?? 1000;
				const delay    = parseFloat( block?.style?.animationDelay?.replace( 's', '' ) ) * 1000 ?? 0;

				setTimeout( () => {
					block.style.opacity   = '';
					block.style.transform = '';
				}, duration + delay );

				observer.unobserve( block );
			}

		} );
	}, {
		rootMargin: window?.blockify?.animationOffset ?? '0px 0px 50px 0px'
	} );

	const animatedBlocks: NodeListOf<HTMLElement> = document.querySelectorAll( '.has-animation' );

	for ( const block of animatedBlocks ) {
		observer.observe( block );
	}

} )();
