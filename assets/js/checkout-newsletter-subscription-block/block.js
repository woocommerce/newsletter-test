/**
 * External dependencies
 */
import { useEffect, useState } from '@wordpress/element';
import { CheckboxControl } from '@woocommerce/blocks-checkout';

const Block = ( { text, checkoutExtensionData } ) => {
	const [ checked, setChecked ] = useState( false );
	const { setExtensionData } = checkoutExtensionData;

	useEffect( () => {
		setExtensionData( 'newsletter-test', 'optin', checked );
	}, [ checked, setExtensionData ] );

	return (
		<CheckboxControl
			id="subscribe-to-newsletter"
			checked={ checked }
			onChange={ setChecked }
		>
			<span
				dangerouslySetInnerHTML={ {
					__html: text,
				} }
			/>
		</CheckboxControl>
	);
};

export default Block;
