const { registerPlugin } = wp.plugins;
const { createElement } = wp.element;
import GuestAuthorsPlugin from './guest-authors';

registerPlugin('dev-guest-authors', {
  render() {
    return createElement(
        GuestAuthorsPlugin,
        {}
    );
  }
})