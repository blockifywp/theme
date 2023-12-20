type Procedure<T extends unknown[]> = ( ...args: T ) => unknown;

function debounce<T extends unknown[]>( func: Procedure<T>, waitMilliseconds: number ): ( ...args: T ) => void {
	let timeoutId: number | undefined;

	return ( ...args: T ) => {
		clearTimeout( timeoutId );
		timeoutId = window.setTimeout( () => {
			func( ...args );
		}, waitMilliseconds );
	};
}

let lastKnownPosition = 0;
let ticking = false;
let maxScroll: number;
let animatedElements: NodeListOf<HTMLElement>;

const updateElements = () => {
	const scrollPercentage = Math.min( Math.max( lastKnownPosition / maxScroll, 0 ), 1 ) * 100;
	document.body.style.setProperty( '--scroll', scrollPercentage.toFixed( 0 ) );

	animatedElements.forEach( ( el ) => {
		const elementTop = el.getBoundingClientRect().top + window.scrollY;
		const elementDistance = ( elementTop - lastKnownPosition ) / window.innerHeight * 100;
		el.style.setProperty( '--scroll-amount', elementDistance.toFixed( 0 ) );
	} );
};

const handleScroll = () => {
	lastKnownPosition = window.scrollY;

	if ( ! ticking ) {
		window.requestAnimationFrame( () => {
			updateElements();
			ticking = false;
		} );

		ticking = true;
	}
};

const initialize = () => {
	maxScroll = document.body.offsetHeight - window.innerHeight;
	animatedElements = document.querySelectorAll<HTMLElement>( '.has-scroll-animation' );
	window.addEventListener( 'scroll', debounce( handleScroll, 10 ) );
};

initialize();
