import {
    useBlockProps,
    InnerBlocks
} from '@wordpress/block-editor';

import { useInstanceId } from '@wordpress/compose';

import './editor.scss';

export default function Edit( { setAttributes } ) {
    const TEMPLATE = [
        [ 'nc/accordion-heading', {} ],
        [ 'nc/accordion-content', {} ],
    ];

    setAttributes({ id: useInstanceId( Edit ) });

	return (
        <div { ...useBlockProps() }>
            <InnerBlocks template={ TEMPLATE } templateLock="all" />
		</div>
	);
}
