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
    const renderButton = function({open}) {
      return el(
          wp.components.Button,
          {onClick: open},
          'Upload file'
      );
    };
    const imgUploadArgs = {
      onSelect: updateImg,
      value: props.attributes.img,
      render: renderButton,
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
        'p',
        [],
        'Thumbnail image'
    );
    const imgDisplay = el(
        'img',
        {
          id: 'img-display',
          src: props.attributes.img_url != '' ?
            props.attributes.img_url : placeHolderUrl,
        }
    );
    const logoDisplay = el(
        'img',
        {
          id: 'logo-display',
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
      render: renderButton,
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
        'p',
        [],
        'News source logo (optional)'
    );
    const titleArgs = {
      onChange: function(value) {
        props.setAttributes({title: value});
      },
      label: 'Article headline',
      value: props.attributes.title,
    };
    const title = el(
        wp.components.TextControl,
        titleArgs
    );
    const linkArgs = {
      onChange: function(value) {
        props.setAttributes({link: value});
      },
      label: 'Link to external article',
      value: props.attributes.link,
    };
    const link = el(
        wp.components.TextControl,
        linkArgs
    );
    const ariaArgs = {
      onChange: function(value) {
        props.setAttributes({aria: value});
      },
      label: 'Detailed description ' +
        '(e.g. "Open external article on AJC titled...")',
      value: props.attributes.aria,
    };
    const aria = el(
        wp.components.TextControl,
        ariaArgs
    );
    return el(
        'div',
        [],
        [title, link, aria,
          imgLabel, imgDisplay, imgWrapped,
          logoLabel, logoDisplay, logoWrapped]
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
