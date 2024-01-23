import {
    useBlockProps,
    InnerBlocks
} from '@wordpress/block-editor';

import './editor.scss';

export default function Edit() {
    const TEMPLATE = [
        [
            'core/columns', [], [
                [ 'core/column', [], [ [ 'nc/featured-image', {} ], ] ],
                [ 'core/column', [], [ [ 'nc/featured-paragraph', {} ], ] ],
            ]
        ]
    ];

    return (
        <div { ...useBlockProps() }>
            <InnerBlocks template={ TEMPLATE } templateLock="all" />
        </div>
    );
}
