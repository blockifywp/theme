const initCounter = () => {
	const mediaQuery = window.matchMedia( '(prefers-reduced-motion)' );

	if ( mediaQuery && mediaQuery.matches ) {
		return;
	}

	const Counter = ( element: HTMLElement ) => {
		if ( element.innerHTML === element.getAttribute( 'data-end' ) ) {
			return;
		}

		const data: { [name: string]: number } = {
			start: parseFloat( element.getAttribute( 'data-start' ) ?? '0' ),
			end: parseFloat( element.getAttribute( 'data-end' ) ?? '0' ),
			delay: parseInt( element.getAttribute( 'data-delay' ) ?? '0' ) || 0,
			duration: parseInt( element.getAttribute( 'data-duration' ) ?? '0' ) || 1,
		};

		let counter = data.start;
		const intervalTime = Math.ceil( ( data.duration * 1000 ) / ( data.end - data.start ) );

		element.innerHTML = counter.toString();

		setTimeout( () => {
			const intervalHandler = () => {
				counter += ( data.end - data.start ) / Math.abs( data.end - data.start );
				element.innerHTML = counter.toString();

				if ( interval && counter === data.end ) {
					clearInterval( interval );
				}
			};

			const interval = setInterval( intervalHandler, intervalTime );
		}, data.delay * 1000 );
	};

	const observer = new IntersectionObserver( ( entries ) => {
		entries.forEach( ( entry: IntersectionObserverEntry ) => {
			const block = entry.target as HTMLElement;

			if ( block && entry.isIntersecting ) {
				Counter( block );
			}
		} );
	}, {
		rootMargin: window?.blockify?.animationOffset ?? '0px 0px 50px 0px',
	} );

	const blocks = document.querySelectorAll( '.is-style-counter' );

	[ ...blocks ].forEach( ( block ) => {
		block.innerHTML = '0';
		observer.observe( block );
	} );
};

document.addEventListener( 'DOMContentLoaded', initCounter );
window.addEventListener( 'resize', initCounter );
