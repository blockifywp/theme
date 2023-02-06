import { __ } from "@wordpress/i18n";
import {
	ButtonGroup,
	Button
} from '@wordpress/components';
import {
	PanelRow,
} from '@wordpress/components';

export const CustomButtonGroup = (
	{
		label = __( 'Width', 'blockify' ),
		width = 'auto',
		widths = [ 'auto', '25%', '50%', '75%', '100%' ],
		isSmall = true,
		setWidth = ( width: string ) => console.log( width ),
		onClick = ( key: string ) => console.log( key ),
	} ) =>
	<PanelRow>
		<ButtonGroup aria-label={ label }>
			{ widths.map( key => <Button
					key={ key }
					isSmall={ isSmall }
					variant={
						key === width ? 'primary' : undefined
					}
					value={ key }
					onClick={ () => {
						if ( key === width ) {
							setWidth( 'auto' );
						} else {
							setWidth( key );
						}

						if ( key === 'default' ) {
							setWidth( '' );
						}

						if ( typeof onClick === 'function' ) {
							onClick( key );
						}
					} }
				>
					{ key === 'auto' ? __( 'Auto', 'blockify' ) : key }
				</Button>
			) }
		</ButtonGroup>

	</PanelRow>;

export default CustomButtonGroup;
