/**
 * External dependencies
 */
import { useEffect, useState } from '@wordpress/element';
import { extensionCartUpdate, CheckboxControl } from '@woocommerce/blocks-checkout';
import { getSetting } from '@woocommerce/settings';

const { optinDefaultText } = getSetting( 'newsletter-test_data', '' );

const Block = ( { children, checkoutExtensionData } ) => {
	const [ checked, setChecked ] = useState( false );
	const { setExtensionData } = checkoutExtensionData;

	useEffect( () => {
		setExtensionData( 'newsletter-test', 'optin', checked );
	}, [ checked, setExtensionData ] );

	return (
		<CheckboxControl
			id="subscribe-to-newsletter"
			checked={ checked }
			onChange={ () => {
				setChecked();
				extensionCartUpdate({
					namespace: 'woocommerce-newsletter-plugin',
					data: {
						hello: 'there'
					}
				})

			}}>
			{ children || optinDefaultText }
		</CheckboxControl>
	);
};

export default Block;
