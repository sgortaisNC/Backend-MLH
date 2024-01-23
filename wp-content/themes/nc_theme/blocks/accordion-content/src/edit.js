import {
    useBlockProps,
    InnerBlocks
} from '@wordpress/block-editor';

import './editor.scss';

export default function Edit( { setAttributes, context } ) {
    const TEMPLATE = [
        [ 'nc/accordion-paragraph', {} ],
    ];

    const ALLOWED_BLOCKS = ['nc/accordion-paragraph', 'nc/accordion-list', 'nc/accordion-table'];

    setAttributes({ id: context[ 'nc-accordion/id' ] });

	return (
        <div { ...useBlockProps() }>
            <InnerBlocks template={ TEMPLATE } allowedBlocks={ ALLOWED_BLOCKS } templateLock={ false } />
		</div>
	);
}
