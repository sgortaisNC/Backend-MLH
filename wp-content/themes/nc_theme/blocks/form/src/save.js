import { useBlockProps } from '@wordpress/block-editor';
import { RawHTML } from '@wordpress/element';

export default function save( { attributes } ) {
    const shortcode = '[forminator_form id="' + attributes.form_id + '"]';

	return (
		<div { ...useBlockProps.save() }>
            <RawHTML>{ shortcode }</RawHTML>
            <a href="javascript:history.back();" className="back" title="Retour à la page précédente">
                Retour
            </a>
		</div>
	);
}
