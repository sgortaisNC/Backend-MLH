import {
    useBlockProps,
    PlainText
} from '@wordpress/block-editor';

import { useInstanceId } from '@wordpress/compose';

import './editor.scss';

export default function Edit( { attributes, setAttributes } ) {
    const instanceId = useInstanceId( Edit );
    const inputId = `blocks-youtube-input-${ instanceId }`;

    return (
        <div { ...useBlockProps( { className: 'components-placeholder' } ) }>
            <label htmlFor={ inputId } className="components-placeholder__label">
                YouTube
            </label>
            <PlainText
                id={ inputId }
                className="blocks-shortcode__textarea"
                placeholder="Saisissez ici l'identifiant de la vidéo..."
                onChange={ ( video_id ) => setAttributes( { video_id } ) }
                value={ attributes.video_id }
            />
        </div>
    );
}
