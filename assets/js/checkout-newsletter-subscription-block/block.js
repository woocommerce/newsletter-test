/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';
import { CheckboxControl } from '@wordpress/components';

const Block = ( { checkoutSubmitData, checkoutExtensionData } ) => {
	const [ checked, setChecked ] = useState( false );
	const { isDisabled } = checkoutSubmitData;
	const { setExtensionData } = checkoutExtensionData;

	useEffect( () => {
		setExtensionData( 'newsletter-extension', 'newsletter', checked );
	}, [ checked, setExtensionData ] );

	return (
		<CheckboxControl
			id="subscribe-to-newsletter"
			checked={ checked }
			label={ __(
				'I want to receive updates about products and promotions.',
				'woo-gutenberg-products-block'
			) }
			onChange={ ( value ) => {
				setChecked( value );
				console.log( value );
			} }
			disabled={ isDisabled }
		/>
	);
};

export default Block;
