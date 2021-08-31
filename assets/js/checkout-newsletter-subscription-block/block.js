/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';
import { CheckboxControl } from '@wordpress/components';

const Block = ( { checkoutExtensionData } ) => {
	const [ checked, setChecked ] = useState( false );
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
			} }
		/>
	);
};

export default Block;
