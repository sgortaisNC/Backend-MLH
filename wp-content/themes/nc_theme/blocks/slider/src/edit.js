import {
    useBlockProps,
    InnerBlocks
} from '@wordpress/block-editor';

import './editor.scss';

export default function Edit() {
    const TEMPLATE = [
        [ 'nc/slider-image', {} ],
        [ 'nc/slider-image', {} ],
        [ 'nc/slider-image', {} ],
    ];

    const ALLOWED_BLOCKS = ['nc/slider-image'];

    return (
        <div { ...useBlockProps() }>
            <label className="components-placeholder__label">
                Slider
            </label>
            <InnerBlocks template={ TEMPLATE } allowedBlocks={ ALLOWED_BLOCKS } />
        </div>
    );
}
