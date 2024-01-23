import { dispatch, useSelect } from '@wordpress/data';
import { useBlockProps } from '@wordpress/block-editor';
import { useInstanceId } from '@wordpress/compose';
import { SelectControl } from '@wordpress/components';

import './editor.scss';

dispatch('core').addEntities([{
    name: 'forms',
    kind: 'nc/v1',
    baseURL: '/nc/v1/forms',
}]);

export default function Edit( { attributes, setAttributes } ) {
    const instanceId = useInstanceId( Edit );
    const inputId = `blocks-iframe-input-${ instanceId }`;
    const forms = useSelect(select => select('core').getEntityRecords('nc/v1', 'forms', {}), []);

    return (
        <div { ...useBlockProps( { className: 'components-placeholder' } ) }>
            <label htmlFor={ inputId } className="components-placeholder__label">
                Formulaire
            </label>
            <SelectControl
                id={ inputId }
                value={ attributes.form_id }
                options={ forms }
                onChange={ ( form_id ) => setAttributes( { form_id } ) }
            />
        </div>
    );
}
