import {
    useBlockProps,
    InnerBlocks
} from '@wordpress/block-editor';

import './editor.scss';

export default function Edit() {
    const TEMPLATE = [
        [ 'core/list', {} ]
    ];

	return (
        <div { ...useBlockProps() }>
            <InnerBlocks template={ TEMPLATE } templateLock="insert" />
		</div>
	);
}
