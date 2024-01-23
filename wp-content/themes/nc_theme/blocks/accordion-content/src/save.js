import {
    useBlockProps,
    InnerBlocks
} from '@wordpress/block-editor';

export default function save( { attributes } ) {
    return (
        <div { ...useBlockProps.save( { className: 'accordion-collapse collapse' } ) } id={ `collapse-${ attributes.id }` }>
            <InnerBlocks.Content />
        </div>
    );
}
