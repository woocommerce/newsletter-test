/**
 * External dependencies
 */
import { useEffect, useState } from '@wordpress/element';
import { CheckboxControl } from '@woocommerce/blocks-checkout';
import { getSetting } from '@woocommerce/settings';
import { useSelect, useDispatch } from '@wordpress/data';

const { optinDefaultText } = getSetting( 'newsletter-test_data', '' );

const Block = ( { children, checkoutExtensionData } ) => {
	const [ checked, setChecked ] = useState( false );
	const { setExtensionData } = checkoutExtensionData;

	const { setValidationErrors, clearValidationError } = useDispatch(
		'wc/store/validation'
	);

	useEffect( () => {
		setExtensionData( 'newsletter-test', 'optin', checked );
		if ( ! checked ) {
			setValidationErrors( {
				'newsletter-test': {
					message: 'Please tick the box',
					hidden: false,
				},
			} );
			return;
		}
		clearValidationError( 'newsletter-test' );
	}, [
		clearValidationError,
		setValidationErrors,
		checked,
		setExtensionData,
	] );

	const { getValidationError } = useSelect( ( select ) => {
		const store = select( 'wc/store/validation' );
		return {
			getValidationError: store.getValidationError(),
		};
	} );

	const errorMessage = getValidationError( 'newsletter-test' )?.message;

	return (
		<>
			<CheckboxControl
				id="subscribe-to-newsletter"
				checked={ checked }
				onChange={ setChecked }
			>
				{ children || optinDefaultText }
			</CheckboxControl>

			{ errorMessage && (
				<div>
					<span role="img" aria-label="Warning emoji">
						⚠️
					</span>
					{ errorMessage }
				</div>
			) }
		</>
	);
};

export default Block;
