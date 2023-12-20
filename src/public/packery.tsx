import Packery from 'packery';
import Draggabilly from 'draggabilly';

const divs = document.querySelectorAll( '.packery' ) as NodeListOf<HTMLDivElement>;

divs.forEach( ( div ) => {
	const itemSelector = div.getAttribute( 'data-item-selector' ) ?? '.wp-block-group';
	const gutter = div.getAttribute( 'data-gutter' ) ?? 16;
	const originTop = div.getAttribute( 'data-origin-top' ) ?? true;
	const draggable = div.getAttribute( 'data-draggable' ) || div.classList.contains( 'is-draggable' );

	const pckry = new Packery(
		div,
		{
			itemSelector,
			gutter,
			originTop,
		}
	);

	if ( draggable ) {
		pckry.items.forEach( ( item: any ): void => {
			pckry.bindDraggabillyEvents( new Draggabilly( item.element ) );
		} );
	}
} );
