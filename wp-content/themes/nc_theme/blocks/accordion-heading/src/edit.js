import {
    useBlockProps,
    InnerBlocks
} from '@wordpress/block-editor';

import './editor.scss';

export default function Edit( { setAttributes, context } ) {
    const TEMPLATE = [
        [ 'core/heading', { placeholder: 'Saisissez ici le titre de l\'accordéon...' } ]
    ];

    setAttributes({ id: context[ 'nc-accordion/id' ] });

	return (
        <div { ...useBlockProps() }>
            <InnerBlocks template={ TEMPLATE } templateLock="all" />
		</div>
	);
}
