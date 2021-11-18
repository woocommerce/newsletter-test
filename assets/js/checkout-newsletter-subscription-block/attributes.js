/**
 * External dependencies
 */
import { getSetting } from '@woocommerce/settings';

const { optinDefaultText } = getSetting( 'newsletter-test_data', '' );

export default {
	text: {
		type: 'string',
		default: optinDefaultText,
	},
};
