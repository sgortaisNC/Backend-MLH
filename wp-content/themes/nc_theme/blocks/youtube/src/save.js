import { useBlockProps } from '@wordpress/block-editor';

export default function save( { attributes } ) {
    return (
        <div
            { ...useBlockProps.save( { className: 'youtube_player' } ) }
            videoID={ attributes.video_id }
            theme="dark"
            rel="1"
            controls="1"
            showinfo="1"
            autoplay="0"
            mute="0"
            loop="0"
            loading="0">
        </div>
    );
}
