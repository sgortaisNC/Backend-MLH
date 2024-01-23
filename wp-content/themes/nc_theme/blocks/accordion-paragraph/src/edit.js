import {
    useBlockProps,
    InnerBlocks
} from '@wordpress/block-editor';

import './editor.scss';

export default function Edit() {
    const TEMPLATE = [
        [ 'core/paragraph', { placeholder: 'Saisissez ici le contenu de l\'accordéon...' } ]
    ];

	return (
        <div { ...useBlockProps() }>
            <InnerBlocks template={ TEMPLATE } templateLock="insert" />
		</div>
	);
}
