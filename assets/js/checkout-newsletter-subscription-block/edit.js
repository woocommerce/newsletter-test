/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	RichText,
	InspectorControls,
} from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { CheckboxControl } from '@woocommerce/blocks-checkout';

/**
 * Internal dependencies
 */
import './style.scss';

export const Edit = ( { attributes, setAttributes } ) => {
	const { text } = attributes;
	const blockProps = useBlockProps();
	return (
		<div { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Block options', 'newsletter-test' ) }>
					Options for the block go here.
				</PanelBody>
			</InspectorControls>
			<CheckboxControl
				id="newsletter-text"
				checked={ false }
				disabled={ true }
			/>
			<RichText
				value={ text }
				onChange={ ( value ) => setAttributes( { text: value } ) }
			/>
		</div>
	);
};

export const Save = () => {
	return <div { ...useBlockProps.save() } />;
};
