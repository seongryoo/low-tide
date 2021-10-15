const { PluginDocumentSettingPanel } = wp.editPost;
const { PanelRow, TextControl, Button } = wp.components;
const { MediaUpload, MediaUploadCheck } = wp.blockEditor;
const { createElement, useState } = wp.element;
const { compose } = wp.compose;
const { withSelect, withDispatch } = wp.data;
const { Icon } = wp.components;

const el = createElement;

const  GuestAuthorsPlugin = ({
  postType, 
  postMeta, 
  setPostMeta
}) => {
  const [metaName, setMetaName] = useState(postMeta.dev_guest_author_name);
  const [metaUrl, setMetaUrl] = useState(postMeta.dev_guest_author_img);
  const [metaImage, setMetaImage] = useState({});
  const uploadIcon = el(
      Icon,
      {
        icon: 'upload',
      }
  );
  const mediaUpload = el(
      MediaUpload,
      {
        onSelect: (media) => {
          setMetaUrl(media.url);
          setMetaImage(media);
          setPostMeta({
            dev_guest_author_img: media.url,
            dev_guest_author_name: metaName,
          });
        },
        value: metaUrl,
        render: ({open}) => {
          return el(
              Button,
              {
                onClick: open,
                id: 'imgButton',
                className: 'upload-button is-secondary',
                style: {
                  marginTop: '0.5rem',
                },
              },
              [uploadIcon, 'Upload author image']
          );
        }
      }
  );
  const mediaCheck = el(
      MediaUploadCheck,
      {},
      mediaUpload
  );
  const imgLabel = el('label', {for: 'imgButton'}, 'Author image:');
  const imgDisplay = el(
      'img',
      {
        id: 'img-display',
        class: 'uploaded-image-display',
        src: metaUrl,
        alt: metaImage.alt,
        style: {
          maxWidth: '100%',
          marginTop: '1rem',
        },
      }
  );
  const imgDelete = el(
      Button,
      {
        onClick: () => {
          setMetaImage({});
          setMetaUrl('');
          setPostMeta({
            dev_guest_author_img: '',
            dev_guest_author_name: metaName,
          });
        },
        isDestructive: true,
        style: {
          marginTop: '0.5rem',
        }
      },
      'Remove author image'
  );
  const authorImage = el(
      'div',
      {},
      [
        imgLabel, 
        mediaCheck, 
        metaUrl != '' ? imgDisplay : null,
        metaUrl != '' ? imgDelete : null,
      ]
  );
  const authorName = el(
      TextControl,
      {
        label: 'Author name:',
        value: metaName,
        onChange: (value) => {
          setMetaName(value);
          setPostMeta({
            dev_guest_author_img: value,
            dev_guest_author_name: metaName,
          });
        },
        style: {
          maxWidth: '100%',
        },
      }
  )
  return el(
      PluginDocumentSettingPanel,
      {
        title: 'Guest Author',
        initialOpen: 'true',
      },
      [
        el(
            PanelRow,
            {},
            [authorName]
        ),
        el(
            PanelRow,
            {
              style: {
                marginTop: '0.5rem',
              }
            },
            [authorImage]
        ),
      ]
  );
};

export default compose([
  withSelect((select) => {
    return {
      postMeta: select('core/editor').getEditedPostAttribute('meta'),
      postType: select('core/editor').getCurrentPostType(),
    };
  }),
  withDispatch((dispatch) => {
    return {
      setPostMeta(newMeta) {
        dispatch('core/editor').editPost({meta: newMeta});
      }
    }
  }),
])(GuestAuthorsPlugin);