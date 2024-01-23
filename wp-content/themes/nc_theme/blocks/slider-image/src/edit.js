import {
    useBlockProps,
    InnerBlocks
} from '@wordpress/block-editor';

import './editor.scss';

export default function Edit() {
    const TEMPLATE = [
        [ 'core/image', {} ]
    ];

    return (
        <div { ...useBlockProps() }>
            <InnerBlocks template={ TEMPLATE } templateLock="all" />
        </div>
    );
}
