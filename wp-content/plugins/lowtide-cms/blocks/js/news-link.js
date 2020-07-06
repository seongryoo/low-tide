(function(wp, scriptData) {
  const el = wp.element.createElement;
  const registerBlock = wp.blocks.registerBlockType;
  const MediaUpload = wp.blockEditor.MediaUpload;
  const MediaUploadCheck = wp.blockEditor.MediaUploadCheck;
  const newsLinkEdit = function(props) {
    const placeHolderUrl = scriptData.pluginUrl + '/assets/image.png';
    const updateImg = function(media) {
      props.setAttributes({
        img: media.id,
        img_url: media.url,
      });
    };
    const dashiUpload = el(
        'span',
        {
          'className': 'dashicons dashicons-upload',
          'aria-hidden': 'true',
        },
        ''
    );
    const renderImgButton = function({open}) {
      return el(
          wp.components.Button,
          {onClick: open, id: 'imgButton', className: 'upload-button'},
          [dashiUpload, 'Upload file']
      );
    };
    const renderLogoButton = function({open}) {
      return el(
          wp.components.Button,
          {onClick: open, id: 'logoButton', className: 'upload-button'},
          [dashiUpload, 'Upload file']
      );
    };
    const imgUploadArgs = {
      onSelect: updateImg,
      value: props.attributes.img,
      render: renderImgButton,
    };
    const img = el(
        MediaUpload,
        imgUploadArgs
    );
    const imgWrapped = el(
        MediaUploadCheck,
        [],
        img
    );
    const imgLabel = el(
        'label',
        {
          for: 'imgButton',
        },
        'Thumbnail image:'
    );
    const imgDisplay = el(
        'img',
        {
          id: 'img-display',
          class: 'uploaded-image-display',
          src: props.attributes.img_url != '' ?
            props.attributes.img_url : placeHolderUrl,
        }
    );
    const logoDisplay = el(
        'img',
        {
          id: 'logo-display',
          class: 'uploaded-image-display',
          src: props.attributes.logo_url != '' ?
            props.attributes.logo_url : placeHolderUrl,
        }
    );
    const updateLogo = function(media) {
      props.setAttributes({
        logo: media.id,
        logo_url: media.url,
      });
    };
    const logoUploadArgs = {
      onSelect: updateLogo,
      value: props.attributes.logo,
      render: renderLogoButton,
    };
    const logo = el(
        MediaUpload,
        logoUploadArgs
    );
    const logoWrapped = el(
        MediaUploadCheck,
        [],
        logo
    );
    const logoLabel = el(
        'label',
        {
          for: 'logoButton',
        },
        'News source logo:'
    );
    const titleArgs = {
      onChange: function(value) {
        props.setAttributes({title: value});
      },
      label: 'Article headline:',
      value: props.attributes.title,
      placeholder: 'Start typing...',
    };
    const title = el(
        wp.components.TextControl,
        titleArgs
    );
    const linkArgs = {
      onChange: function(value) {
        props.setAttributes({link: value});
      },
      label: 'Link to external article:',
      value: props.attributes.link,
      placeholder: 'Start typing...',
    };
    const link = el(
        wp.components.TextControl,
        linkArgs
    );
    const ariaArgs = {
      onChange: function(value) {
        props.setAttributes({aria: value});
      },
      label: 'Accessible description that can be understood alone: ' +
        '(e.g. "Open external article on AJC titled...")',
      value: props.attributes.aria,
      placeholder: 'Start typing...',
    };
    const aria = el(
        wp.components.TextControl,
        ariaArgs
    );
    const uploadImageBlock = el(
        'div',
        {
          className: 'components-base-control__field upload-image',
        },
        [imgLabel, imgWrapped, imgDisplay]
    );
    const uploadLogoBlock = el(
        'div',
        {
          className: 'components-base-control__field upload-logo',
        },
        [logoLabel, logoWrapped, logoDisplay]
    );
    const uploadImageWrapped = el(
        'div',
        {
          className: 'components-base-control',
        },
        uploadImageBlock
    );
    const uploadLogoWrapped = el(
        'div',
        {
          className: 'components-base-control',
        },
        uploadLogoBlock
    );
    return el(
        'div',
        {
          className: 'news-link-data',
        },
        [title, link, aria, uploadImageWrapped, uploadLogoWrapped]
    );
  };
  const extNewsDataArgs = {
    title: '(SSLS) External news data',
    category: 'lowtide-blocks',
    icon: 'id-alt',
    attributes: {
      title: {
        type: 'string',
        source: 'meta',
        meta: 'post_ext_news_meta_title',
      },
      img: {
        type: 'number',
        source: 'meta',
        meta: 'post_ext_news_meta_img',
      },
      img_url: {
        type: 'string',
        source: 'meta',
        meta: 'post_ext_news_meta_img_url',
      },
      logo: {
        type: 'number',
        source: 'meta',
        meta: 'post_ext_news_meta_logo',
      },
      logo_url: {
        type: 'string',
        source: 'meta',
        meta: 'post_ext_news_meta_logo_url',
      },
      link: {
        type: 'string',
        source: 'meta',
        meta: 'post_ext_news_meta_link',
      },
      aria: {
        type: 'string',
        source: 'meta',
        meta: 'post_ext_news_meta_aria',
      },
    },
    edit: newsLinkEdit,
    save: function(props) {
      return null;
    },
  };
  registerBlock('lowtide/ext-news-data', extNewsDataArgs);
})(window.wp, window.scriptData);
