import {
    useBlockProps,
    InnerBlocks
} from '@wordpress/block-editor';

import './editor.scss';

export default function Edit() {
    const TEMPLATE = [
        [ 'core/heading', { placeholder: 'Saisissez ici le titre mis en lumière...' } ],
        [ 'nc/featured-row', {} ],
    ];

    return (
        <div { ...useBlockProps() }>
            <InnerBlocks template={ TEMPLATE } templateLock="all" />
        </div>
    );
}
