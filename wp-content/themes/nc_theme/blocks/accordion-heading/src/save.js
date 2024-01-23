import {
    useBlockProps,
    InnerBlocks
} from '@wordpress/block-editor';

export default function save( { attributes } ) {
    return (
        <div { ...useBlockProps.save( { className: 'accordion-button collapsed' } ) } data-bs-toggle="collapse" data-bs-target={ `#collapse-${ attributes.id }` }>
            <InnerBlocks.Content />
        </div>
    );
}
