( () => {
	const details = document.querySelectorAll( 'details' );

	for ( const detail of details ) {
		detail.addEventListener( 'click', ( event ) => {

			for ( const otherDetail of details ) {
				if ( otherDetail !== detail ) {
					otherDetail.removeAttribute( 'open' );

					const otherSection = otherDetail.getElementsByTagName( 'section' ).item( 0 );

					if ( otherSection ) {
						otherSection.classList.remove( 'is-open' );
						otherSection.classList.add( 'is-closed' );
					}
				}
			}

			const section = detail.getElementsByTagName( 'section' ).item( 0 );

			if ( ! section ) {
				return;
			}

			section.style.setProperty(
				'--height',
				section.scrollHeight + 'px'
			);

			if ( detail.hasAttribute( 'open' ) ) {
				section.classList.add( 'is-closed' );
				section.classList.remove( 'is-open' );

			} else {
				section.classList.remove( 'is-closed' );
				section.classList.add( 'is-open' );
			}

		} );
	}
} )();
