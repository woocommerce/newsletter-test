/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';
import {
	useCheckoutSubmit,
	useCheckoutExtensionData,
} from '@woocommerce/base-context/hooks';
import { CheckboxControl } from '@wordpress/components';

const Block = () => {
	const [ checked, setChecked ] = useState( false );
	const { isDisabled } = useCheckoutSubmit();
	const { setExtensionData } = useCheckoutExtensionData();

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
			onChange={ () => setChecked( ( value ) => ! value ) }
			disabled={ isDisabled }
		/>
	);
};

export default Block;
