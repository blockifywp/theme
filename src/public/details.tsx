interface DetailsElement extends HTMLElement {
	open: boolean;
}

const getTransitionDuration = (): number => {
	const style = getComputedStyle( document.body );
	const transitionDuration = style.getPropertyValue( '--wp--custom--transition--duration' );

	if ( transitionDuration.includes( 'ms' ) ) {
		return parseFloat( transitionDuration );
	} else if ( transitionDuration.includes( 's' ) ) {
		return parseFloat( transitionDuration ) * 1000; // Convert seconds to milliseconds
	}

	return 300;
};

const handleSticky = ( details: DetailsElement, animateHeight: ( isOpening: boolean ) => void ): void => {
	const getStickyTopValue = (): number => {
		const style = getComputedStyle( details );
		return parseFloat( style.top ) || 0;
	};

	const checkAndCloseIfStuck = () => {
		const stickyTop = getStickyTopValue();
		const detailsRect = details.getBoundingClientRect();

		if ( window.getComputedStyle( details ).position === 'sticky' && detailsRect.top <= stickyTop && details.open ) {
			animateHeight( false ); // Use animateHeight for smooth closing
		}
	};

	window.addEventListener( 'scroll', checkAndCloseIfStuck );
};

const initAccordion = ( el: HTMLElement ): void => {
	const details = el as DetailsElement;
	let animation: Animation | null = null;

	const summary = details.querySelector( 'summary' ) as HTMLElement | null;
	if ( ! summary ) {
		return;
	}

	const calculateInitialMaxHeight = (): number => {
		const style = getComputedStyle( details );
		const borderTopWidth = parseFloat( style.borderTopWidth ) || 0;
		const borderBottomWidth = parseFloat( style.borderBottomWidth ) || 0;
		return summary.offsetHeight + borderTopWidth + borderBottomWidth;
	};

	details.style.maxHeight = `${ calculateInitialMaxHeight() }px`;

	const getContentHeight = (): number => {
		return Array.from( details.children )
			.filter( ( child ) => child !== summary )
			.reduce( ( totalHeight, child ) => {
				const childEl = child as HTMLElement;
				const style = getComputedStyle( childEl );
				const marginTop = parseFloat( style.marginTop ) || 0;
				const marginBottom = parseFloat( style.marginBottom ) || 0;
				return totalHeight + childEl.offsetHeight + marginTop + marginBottom;
			}, 0 );
	};

	const animateHeight = ( isOpening: boolean ) => {
		const contentHeight = getContentHeight();
		const summaryOuterHeight = calculateInitialMaxHeight();
		const startHeight = `${ details.offsetHeight }px`;
		const endHeight = isOpening ? `${ summaryOuterHeight + contentHeight }px` : `${ summaryOuterHeight }px`;

		if ( ! isOpening ) {
			details.classList.add( 'closing' );
		}

		details.style.overflow = 'hidden';
		animation?.cancel();

		animation = details.animate( { maxHeight: [ startHeight, endHeight ] }, {
			duration: getTransitionDuration(),
			easing: 'ease-out',
		} );
		animation.onfinish = () => {
			animation = null;
			details.style.overflow = '';
			details.style.maxHeight = isOpening ? 'none' : `${ summaryOuterHeight }px`;

			if ( ! isOpening ) {
				details.classList.remove( 'closing' );
				details.removeAttribute( 'open' );
			}
		};
	};

	handleSticky( details, animateHeight ); // Pass animateHeight to handleSticky

	summary.addEventListener( 'click', ( e: MouseEvent ) => {
		e.preventDefault();
		const isOpening = ! details.open;
		if ( isOpening ) {
			details.setAttribute( 'open', '' );
		}
		animateHeight( isOpening );
	} );
};

document.querySelectorAll( 'details' ).forEach( ( el ) => {
	if ( el instanceof HTMLElement ) {
		initAccordion( el );
	}
} );
